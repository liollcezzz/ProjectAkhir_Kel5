<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Notifications\PaymentReceivedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class PaymentController extends Controller
{
    public function finish(Request $r)
    {
        $order = Order::findOrFail($r->order_id);
        $order->snap_token = null;
        $order->payment_status = 'paid';
        $order->status = 'paid';
        $order->save();

        Notification::send(
            User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_WAREHOUSE])->get(),
            new PaymentReceivedNotification($order)
        );

        return redirect()->route('customer.orders.show', $order)
            ->with('ok', 'Payment successful! Your order has been paid.');
    }

    public function unfinish(Request $r)
    {
        $order = Order::findOrFail($r->order_id);
        return redirect()->route('customer.checkout.show')
            ->withErrors(['payment' => 'Payment was not completed. Please try again.']);
    }

    public function error(Request $r)
    {
        $order = Order::findOrFail($r->order_id);
        return redirect()->route('customer.checkout.show')
            ->withErrors(['payment' => 'Payment failed. Please try again.']);
    }

    public function notification(Request $r)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash('sha512', $r->order_id . $r->status_code . $r->gross_amount . $serverKey);

        if ($hashed !== $r->signature_key) {
            return response('Invalid signature', 403);
        }

        $order = Order::findOrFail($r->order_id);
        $orderId = $r->order_id;

        if ($r->transaction_status === 'settlement' || $r->transaction_status === 'capture') {
            $order->payment_status = 'paid';
            $order->payment_method = $r->payment_type;
            $order->payment_details = json_encode($r->all());
            $order->status = 'paid';
            $order->save();

            Notification::send(
                User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_WAREHOUSE])->get(),
                new PaymentReceivedNotification($order)
            );
        } elseif (in_array($r->transaction_status, ['deny', 'cancel', 'expire'])) {
            $order->payment_status = 'failed';
            $order->save();
        }

        return response('OK', 200);
    }
}
