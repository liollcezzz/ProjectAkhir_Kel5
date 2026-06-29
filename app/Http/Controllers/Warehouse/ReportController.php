<?php
namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\{Product, StockMovement};
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $r) {
        $from = $r->date('from') ?? today()->subDays(30);
        $to   = $r->date('to')   ?? today()->endOfDay();
        $movements = StockMovement::with('product','user')
            ->whereBetween('created_at', [$from, $to])->latest()->get();
        $products  = Product::orderBy('stock')->get();
        return view('warehouse.reports.index', compact('movements','products','from','to'));
    }

    public function pdf(Request $r) {
        $products = Product::with('categories')->orderBy('name')->get();
        $pdf = Pdf::loadView('warehouse.reports.pdf', compact('products'));
        return $pdf->download('inventory-'.now()->format('Ymd-His').'.pdf');
    }
}
