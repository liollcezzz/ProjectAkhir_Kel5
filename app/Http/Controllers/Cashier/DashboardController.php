<?php
namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;

class DashboardController extends Controller
{
    public function __invoke() {
        $todayTotal = Order::where('cashier_id', auth()->id())
            ->whereDate('created_at', today())->sum('total');
        $todayCount = Order::where('cashier_id', auth()->id())
            ->whereDate('created_at', today())->count();
        $recent = Order::where('cashier_id', auth()->id())->latest()->limit(8)->get();
        return view('cashier.dashboard', compact('todayTotal','todayCount','recent'));
    }
}