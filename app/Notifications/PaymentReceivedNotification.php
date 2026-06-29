<?php
namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentReceivedNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject("Payment Received — {$this->order->code}")
            ->greeting("Hello!");

        if ($notifiable->isCustomer()) {
            $message->line("Your payment for order {$this->order->code} has been received.")
                ->line("Total: Rp " . number_format($this->order->total, 0, ',', '.'))
                ->action('View Order', route('customer.orders.show', $this->order));
        } else {
            $message->line("Payment received for order {$this->order->code}.")
                ->line("Total: Rp " . number_format($this->order->total, 0, ',', '.'))
                ->action('View Order', url('/admin'));
        }

        return $message;
    }

    public function toArray($notifiable)
    {
        return [
            'order_id'   => $this->order->id,
            'order_code' => $this->order->code,
            'message'    => "Payment received for order {$this->order->code}.",
        ];
    }
}
