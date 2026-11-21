<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderNoticeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $template,
        public string $subjectLine,
        public array $order,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->subjectLine);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.'.$this->template,
            text: 'emails.orders.plain',
            with: ['order' => $this->order],
        );
    }
}
