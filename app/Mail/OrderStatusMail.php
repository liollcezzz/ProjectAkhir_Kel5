<?php
namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class OrderStatusMail extends Mailable
{
    use Queueable;

    public function __construct(public Order $order, public string $statusLabel) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Order {$this->order->code} — {$this->statusLabel}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-status',
        );
    }
}
