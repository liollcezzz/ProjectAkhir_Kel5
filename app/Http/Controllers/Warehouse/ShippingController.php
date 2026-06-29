<?php
namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderShippedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusMail;

class ShippingController extends Controller
{
    public function index(Request $r)
    {
        $tab = $r->query('tab', 'to_ship');

        $orders = Order::with('items', 'customer')
            ->where('shipping_method', '!=', 'pickup')
            ->whereNotNull('shipping_method');

        if ($tab === 'to_ship') {
            $orders->whereIn('status', ['paid', 'confirmed', 'pending'])
                   ->where(function ($q) {
                       $q->whereNull('shipping_status')
                         ->orWhere('shipping_status', 'pending');
                   });
        } elseif ($tab === 'shipped') {
            $orders->where('shipping_status', 'shipped');
        } elseif ($tab === 'delivered') {
            $orders->where('shipping_status', 'delivered');
        }

        $orders = $orders->latest()->paginate(15);

        $counts = [
            'to_ship' => Order::whereIn('status', ['paid', 'confirmed', 'pending'])
                ->where('shipping_method', '!=', 'pickup')
                ->whereNotNull('shipping_method')
                ->where(function ($q) {
                    $q->whereNull('shipping_status')
                      ->orWhere('shipping_status', 'pending');
                })
                ->count(),
            'shipped' => Order::where('shipping_status', 'shipped')->count(),
            'delivered' => Order::where('shipping_status', 'delivered')->count(),
        ];

        return view('warehouse.shipping.index', compact('orders', 'tab', 'counts'));
    }

    public function ship(Order $order)
    {
        abort_unless(in_array($order->status, ['paid', 'confirmed', 'pending']), 403);
        abort_if($order->shipping_method === 'pickup', 403);

        return view('warehouse.shipping.ship', compact('order'));
    }

    public function store(Request $r, Order $order)
    {
        abort_unless(in_array($order->status, ['paid', 'confirmed', 'pending']), 403);
        abort_if($order->shipping_method === 'pickup', 403);

        $data = $r->validate([
            'tracking_number' => 'required|string|max:100',
        ]);

        $order->status = 'shipped';
        $order->shipping_status = 'shipped';
        $order->tracking_number = $data['tracking_number'];
        $order->save();

        if ($order->customer) {
            $order->customer->notify(new OrderShippedNotification($order));
        }

        User::whereIn('role', [User::ROLE_ADMIN])->get()->each->notify(new OrderShippedNotification($order));

        return redirect()->route('warehouse.shipping.index', ['tab' => 'shipped'])
            ->with('ok', "Order {$order->code} marked as shipped.");
    }

    public function delivered(Order $order)
    {
        abort_unless($order->shipping_status === 'shipped', 403);

        $order->status = 'completed';
        $order->shipping_status = 'delivered';
        $order->save();

        if ($order->customer) {
            Mail::to($order->customer->email)->send(new OrderStatusMail($order, 'Delivered'));
        }

        return redirect()->route('warehouse.shipping.index', ['tab' => 'delivered'])
            ->with('ok', "Order {$order->code} marked as delivered.");
    }

    public function tracking(Request $r, Order $order)
    {
        abort_unless($order->shipping_status === 'shipped', 403);
        abort_if($order->shipping_method === 'pickup', 403);

        $data = $r->validate([
            'tracking_number' => 'required|string|max:100',
        ]);

        $order->tracking_number = $data['tracking_number'];
        $order->save();

        return redirect()->route('warehouse.shipping.index', ['tab' => 'shipped'])
            ->with('ok', "Tracking number updated for {$order->code}.");
    }
}
