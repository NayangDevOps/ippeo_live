<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPlacedAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Order #' . $this->order->order_number . ' — ₹' . number_format((float) $this->order->total, 0),
            replyTo: array_filter([$this->order->customer_email]),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-admin',
        );
    }
}
