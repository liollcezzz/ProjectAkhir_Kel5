<?php
namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderConfirmedNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order, public ?string $confirmedBy = null) {}

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject("Order Confirmed — {$this->order->code}")
            ->greeting("Hello!");

        if ($notifiable->isCustomer()) {
            $message->line("Your order {$this->order->code} has been confirmed.")
                ->line("Total: Rp " . number_format($this->order->total, 0, ',', '.'))
                ->action('View Order', route('customer.orders.show', $this->order));
        } else {
            $message->line("Order {$this->order->code} has been confirmed by cashier.")
                ->line("Total: Rp " . number_format($this->order->total, 0, ',', '.'))
                ->action('View Order', url('/admin'));
        }

        return $message;
    }

    public function toArray($notifiable)
    {
        return [
            'order_id'      => $this->order->id,
            'order_code'    => $this->order->code,
            'status'        => $this->order->status,
            'message'       => "Order {$this->order->code} has been confirmed by cashier.",
            'confirmed_by'  => $this->confirmedBy,
        ];
    }
}
