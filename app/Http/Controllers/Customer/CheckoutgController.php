<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\{Order, OrderItem, Product, StockMovement};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        return view('customer.checkout', compact('cart','products','total'));
    }

    public function place(Request $r) {
        $cart = session('cart', []);
        if (empty($cart)) return redirect()->route('cart.index');

        $data = $r->validate([
            'customer_name'  => 'required|string|max:120',
            'customer_phone' => 'required|string|max:32',
            'notes'          => 'nullable|string|max:500',
        ]);

        $order = DB::transaction(function () use ($cart, $data) {
            $subtotal = 0;
            $rows = [];
            foreach ($cart as $id => $qty) {
                $p = Product::lockForUpdate()->find($id);
                if (!$p || $p->stock < $qty) abort(422, "Insufficient stock.");
                $line = $p->price * $qty;
                $subtotal += $line;
                $rows[] = [$p, $qty, $line];
            }
            $order = Order::create([
                'code'           => Order::generateCode('online'),
                'user_id'        => auth()->id(),
                'channel'        => 'online',
                'status'         => 'pending',
                'subtotal'       => $subtotal,
                'total'          => $subtotal,
                'customer_name'  => $data['customer_name'],
                'customer_phone' => $data['customer_phone'],
                'notes'          => $data['notes'] ?? null,
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
        return redirect()->route('customer.orders.show', $order)->with('ok','Order placed!');
    }
}
