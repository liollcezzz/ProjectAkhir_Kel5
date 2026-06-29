<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Order, Product, User};
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke() {
        $totalSales = Order::where('status','!=','cancelled')->sum('total');
        $todaySales = Order::whereDate('created_at', today())->where('status','!=','cancelled')->sum('total');
        $totalStaff = User::whereIn('role',['cashier','warehouse'])->count();
        $lowStock   = Product::where('stock','<',Product::LOW_STOCK_THRESHOLD)->orderBy('stock')->limit(10)->get();
        $recent     = Order::with('items')->latest()->limit(10)->get();
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        $notificationsCount = $user ? $user->unreadNotifications()->count() : 0;
        return view('admin.dashboard', compact('totalSales','todaySales','totalStaff','lowStock','recent','notificationsCount'));
    }
}
