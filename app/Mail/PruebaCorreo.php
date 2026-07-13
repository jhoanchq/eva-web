<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PruebaCorreo extends Mailable
{
    use Queueable, SerializesModels;

    public string $subjectText;
    public string $messageText;
    public ?string $tag;

    public function __construct(string $subject, string $message, ?string $tag = null)
    {
        $this->subjectText = $subject;
        $this->messageText = $message;
        $this->tag = $tag;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectText,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.prueba',
        );
    }
}
