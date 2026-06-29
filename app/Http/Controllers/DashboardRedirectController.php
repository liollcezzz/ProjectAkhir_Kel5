<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardRedirectController extends Controller
{
    public function __invoke(Request $r) {
        return match ($r->user()->role) {
            'admin'     => redirect()->route('admin.dashboard'),
            'cashier'   => redirect()->route('cashier.dashboard'),
            'warehouse' => redirect()->route('warehouse.dashboard'),
            default     => redirect()->route('customer.orders.index'),
        };
    }
}
