<?php
namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\{Category, Product};
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke() {
        $totalSku       = Product::count();
        $totalCategories= Category::count();
        $outOfStock     = Product::where('stock', 0)->orderBy('name')->get();
        $lowStock       = Product::where('stock','>',0)->where('stock','<',Product::LOW_STOCK_THRESHOLD)->orderBy('stock')->get();
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        $notificationsCount = $user ? $user->unreadNotifications()->count() : 0;
        return view('warehouse.dashboard', compact('totalSku','totalCategories','outOfStock','lowStock','notificationsCount'));
    }
}
