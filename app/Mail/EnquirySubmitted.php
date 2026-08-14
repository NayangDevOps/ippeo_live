<?php

namespace App\Mail;

use App\Models\Enquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EnquirySubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Enquiry $enquiry) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Website Enquiry from ' . $this->enquiry->name,
            replyTo: array_filter([$this->enquiry->email]),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.enquiry',
        );
    }
}
