<?php
namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderShippedNotification extends Notification
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
            ->subject("Order Shipped — {$this->order->code}")
            ->greeting("Hello!");

        if ($notifiable->isCustomer()) {
            $message->line("Your order {$this->order->code} has been shipped.")
                ->line("Tracking Number: {$this->order->tracking_number}")
                ->action('Track Order', route('customer.orders.show', $this->order));
        } else {
            $message->line("Order {$this->order->code} has been shipped.")
                ->line("Tracking: {$this->order->tracking_number}")
                ->action('View Order', url('/admin'));
        }

        return $message;
    }

    public function toArray($notifiable)
    {
        return [
            'order_id'       => $this->order->id,
            'order_code'     => $this->order->code,
            'message'        => "Order {$this->order->code} has been shipped.",
            'tracking_number'=> $this->order->tracking_number,
        ];
    }
}
