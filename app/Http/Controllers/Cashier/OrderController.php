<?php
namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\{Order, User};
use App\Notifications\OrderConfirmedNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    public function index(Request $r) {
        $status = $r->query('status', 'all');
        $orders = Order::with(['items','customer'])
            ->where(function($q) {
                $q->where('cashier_id', Auth::id())
                  ->orWhere(function($q2) {
                      $q2->whereNull('cashier_id')
                         ->where('channel','online');
                  });
            })
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->where('status','!=','cancelled')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $statuses = ['all' => 'All', 'pending' => 'Pending', 'confirmed' => 'Confirmed'];
        return view('cashier.orders.index', compact('orders','statuses','status'));
    }

    public function show(Order $order) {
        abort_unless(
            $order->status !== 'cancelled' &&
            (is_null($order->cashier_id) || $order->cashier_id === Auth::id()),
            403
        );
        $order->load('items');
        return view('cashier.orders.show', compact('order'));
    }

    public function confirm(Request $r, Order $order) {
        abort_unless($order->status !== 'cancelled', 403);
        // Only allow if unassigned or already assigned to this cashier
        if (!is_null($order->cashier_id) && $order->cashier_id !== Auth::id()) {
            abort(403);
        }

        $order->cashier_id = Auth::id();
        $order->status = 'confirmed';
        $order->save();

        $recipients = User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_WAREHOUSE])->get();
        if ($order->customer) $recipients->push($order->customer);
        Notification::send($recipients, new OrderConfirmedNotification($order, Auth::user()?->name));

        return redirect()->route('cashier.orders.show', $order)->with('ok','Order claimed and confirmed.');
    }

    public function pdf(Order $order) {
        abort_unless(
            $order->status !== 'cancelled' &&
            (is_null($order->cashier_id) || $order->cashier_id === Auth::id()),
            403
        );
        $order->load('items');
        $sm = config('shipping.methods.'.$order->shipping_method);
        $pdf = Pdf::loadView('pdf.invoice', compact('order', 'sm'));
        return $pdf->download('invoice-'.$order->code.'.pdf');
    }
}
