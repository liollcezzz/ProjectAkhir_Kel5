<?php
namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $r) {
        $from = $r->date('from') ?? today()->startOfMonth();
        $to   = $r->date('to')   ?? today()->endOfDay();
        $orders = Order::where('cashier_id', auth()->id())
            ->whereBetween('created_at', [$from, $to])
            ->where('status','!=','cancelled')
            ->latest()->get();
        $total = $orders->sum('total');
        return view('cashier.reports.index', compact('orders','from','to','total'));
    }

    public function pdf(Request $r) {
        $from = $r->date('from') ?? today()->startOfMonth();
        $to   = $r->date('to')   ?? today()->endOfDay();
        $orders = Order::where('cashier_id', auth()->id())
            ->whereBetween('created_at', [$from, $to])
            ->where('status','!=','cancelled')->latest()->get();
        $total = $orders->sum('total');
        $pdf = Pdf::loadView('cashier.reports.pdf', compact('orders','from','to','total'));
        return $pdf->download('sales-report-'.now()->format('Ymd-His').'.pdf');
    }
}
