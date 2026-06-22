<?php
namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\{Order, OrderItem, Product, StockMovement};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index(Request $r) {
        $q = trim((string) $r->get('q'));
        $products = Product::query()
            ->when($q !== '', fn($qq) => $qq->where(function($s) use ($q) {
                $s->where('name','like',"%$q%")->orWhere('sku','like',"%$q%");
            }))
            ->where('is_active', true)->where('stock','>',0)
            ->orderBy('name')->limit(60)->get();
        return view('cashier.pos', compact('products','q'));
    }

    public function store(Request $r) {
        $data = $r->validate([
            'items'           => 'required|array|min:1',
            'items.*.id'      => 'required|exists:products,id',
            'items.*.qty'     => 'required|integer|min:1',
            'amount_paid'     => 'required|numeric|min:0',
            'customer_name'   => 'nullable|string|max:120',
            'customer_phone'  => 'nullable|string|max:32',
        ]);

        $order = DB::transaction(function () use ($data) {
            $subtotal = 0;
            $lines = [];
            foreach ($data['items'] as $row) {
                $p = Product::lockForUpdate()->findOrFail($row['id']);
                if ($p->stock < $row['qty']) {
                    abort(422, "Insufficient stock for {$p->name}");
                }
                $lineTotal = $p->price * $row['qty'];
                $subtotal += $lineTotal;
                $lines[] = [$p, $row['qty'], $lineTotal];
            }
            $total = $subtotal; // extend with tax if needed
            $order = Order::create([
                'code'           => Order::generateCode('pos'),
                'cashier_id'     => auth()->id(),
                'channel'        => 'pos',
                'status'         => 'completed',
                'subtotal'       => $subtotal,
                'total'          => $total,
                'amount_paid'    => $data['amount_paid'],
                'change_due'     => max(0, $data['amount_paid'] - $total),
                'customer_name'  => $data['customer_name'] ?? null,
                'customer_phone' => $data['customer_phone'] ?? null,
            ]);
            foreach ($lines as [$p, $qty, $lt]) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $p->id,
                    'product_name' => $p->name,
                    'product_sku'  => $p->sku,
                    'unit_price'   => $p->price,
                    'quantity'     => $qty,
                    'line_total'   => $lt,
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

        return redirect()->route('cashier.pos.receipt', $order)->with('ok','Sale recorded.');
    }

    public function receipt(Order $order) {
        abort_unless($order->channel === 'pos', 404);
        return view('cashier.receipt', compact('order'));
    }
}
