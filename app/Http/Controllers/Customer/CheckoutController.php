<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\{Order, OrderItem, Product, StockMovement};
use App\Models\User;
use App\Notifications\PaymentReceivedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class CheckoutController extends Controller
{
    public function show() {
        $cart = session('cart', []);
        if (empty($cart)) return redirect()->route('cart.index');
        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');
        $total = 0;
        foreach ($cart as $id => $qty) {
            if (isset($products[$id])) $total += $products[$id]->price * $qty;
        }
        $shippingMethods = config('shipping.methods');
        return view('customer.checkout', compact('cart','products','total','shippingMethods'));
    }

    public function place(Request $r) {
        $cart = session('cart', []);
        if (empty($cart)) return redirect()->route('cart.index');

        $data = $r->validate([
            'customer_name'    => 'required|string|max:120',
            'customer_phone'   => 'required|string|max:32',
            'shipping_method'  => 'required|string|in:' . implode(',', array_keys(config('shipping.methods'))),
            'shipping_address' => 'required_unless:shipping_method,pickup|nullable|string|max:500',
            'notes'            => 'nullable|string|max:500',
        ]);

        $shippingMethods = config('shipping.methods');
        $shippingCost = $shippingMethods[$data['shipping_method']]['cost'] ?? 0;
        $shippingAddress = ($data['shipping_method'] === 'pickup') ? null : ($data['shipping_address'] ?? null);

        $order = DB::transaction(function () use ($cart, $data, $shippingCost, $shippingAddress) {
            $subtotal = 0;
            $rows = [];
            foreach ($cart as $id => $qty) {
                $p = Product::lockForUpdate()->find($id);
                if (!$p || $p->stock < $qty) abort(422, "Insufficient stock.");
                $line = $p->price * $qty;
                $subtotal += $line;
                $rows[] = [$p, $qty, $line];
            }
            $total = $subtotal + $shippingCost;
            $order = Order::create([
                'code'             => Order::generateCode('online'),
                'user_id'          => auth()->id(),
                'channel'          => 'online',
                'status'           => 'pending',
                'subtotal'         => $subtotal,
                'shipping_cost'    => $shippingCost,
                'total'            => $total,
                'customer_name'    => $data['customer_name'],
                'customer_phone'   => $data['customer_phone'],
                'notes'            => $data['notes'] ?? null,
                'shipping_method'  => $data['shipping_method'],
                'shipping_address' => $shippingAddress,
            ]);
            foreach ($rows as [$p, $qty, $line]) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $p->id,
                    'product_name' => $p->name,
                    'product_sku'  => $p->sku,
                    'unit_price'   => $p->price,
                    'quantity'     => $qty,
                    'line_total'   => $line,
                ]);
                $p->decrement('stock', $qty);
                StockMovement::create([
                    'product_id'      => $p->id,
                    'user_id'         => auth()->id(),
                    'type'            => 'sale',
                    'quantity_change' => -$qty,
                    'stock_after'     => $p->fresh()->stock,
                    'reference'       => $order->code,
                ]);
            }
            return $order;
        });

        session()->forget('cart');
        return redirect()->route('customer.orders.show', $order)->with('ok', 'Order placed!');
    }

    public function pay(Order $order) {
        abort_unless($order->user_id === auth()->id(), 403);
        abort_unless($order->payment_status === 'unpaid', 403);

        $clientKey = config('midtrans.client_key');
        $hasMidtrans = !empty(config('midtrans.server_key')) && !empty($clientKey);

        // Generate Snap token on demand if Midtrans is configured
        $snapToken = null;
        if ($hasMidtrans && !$order->snap_token) {
            try {
                \Midtrans\Config::$serverKey    = config('midtrans.server_key');
                \Midtrans\Config::$isProduction = config('midtrans.is_production');
                \Midtrans\Config::$isSanitized  = config('midtrans.is_sanitized');
                \Midtrans\Config::$is3ds        = config('midtrans.is_3ds');

                $params = [
                    'transaction_details' => [
                        'order_id'     => $order->id,
                        'gross_amount' => (int) $order->total,
                    ],
                    'customer_details' => [
                        'first_name' => $order->customer_name,
                        'phone'      => $order->customer_phone,
                    ],
                    'item_details' => [],
                ];

                foreach ($order->items as $item) {
                    $params['item_details'][] = [
                        'id'       => $item->product_sku,
                        'price'    => (int) $item->unit_price,
                        'quantity' => $item->quantity,
                        'name'     => $item->product_name,
                    ];
                }
                if ($order->shipping_cost > 0) {
                    $params['item_details'][] = [
                        'id'       => 'shipping',
                        'price'    => (int) $order->shipping_cost,
                        'quantity' => 1,
                        'name'     => 'Shipping',
                    ];
                }

                $snapToken = \Midtrans\Snap::getSnapToken($params);
                $order->snap_token = $snapToken;
                $order->save();
            } catch (\Exception $e) {
                $hasMidtrans = false;
            }
        } elseif ($order->snap_token) {
            $snapToken = $order->snap_token;
        }

        return view('customer.pay', compact('order', 'clientKey', 'snapToken', 'hasMidtrans'));
    }

    public function confirmManual(Order $order) {
        abort_unless($order->user_id === auth()->id(), 403);
        abort_unless($order->payment_status === 'unpaid', 403);

        $order->payment_status = 'paid';
        $order->payment_method = 'manual_transfer';
        $order->status = 'paid';
        $order->save();

        Notification::send(
            User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_WAREHOUSE])->get(),
            new PaymentReceivedNotification($order)
        );

        return redirect()->route('customer.orders.show', $order)
            ->with('ok', 'Payment confirmed! Your order is now being processed.');
    }
}
