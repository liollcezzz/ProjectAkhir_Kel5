<?php
namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke() {
        $todayTotal = Order::where('cashier_id', Auth::id())
            ->whereDate('created_at', today())->sum('total');
        $todayCount = Order::where('cashier_id', Auth::id())
            ->whereDate('created_at', today())->count();
        $pendingOrders = Order::whereNull('cashier_id')
            ->where('channel','online')
            ->where('status','!=','cancelled')
            ->count();
        $recent = Order::where('cashier_id', Auth::id())->latest()->limit(8)->get();
        return view('cashier.dashboard', compact('todayTotal','todayCount','pendingOrders','recent'));
    }
}
