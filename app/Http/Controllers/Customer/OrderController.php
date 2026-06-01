<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function index() {
        $orders = Order::with('items')->where('user_id', auth()->id())->latest()->paginate(10);
        return view('customer.orders', compact('orders'));
    }

    public function show(Order $order) {
        abort_unless($order->user_id === auth()->id(), 403);
        $order->load('items');
        return view('customer.order', compact('order'));
    }
}
