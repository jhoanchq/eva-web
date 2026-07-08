<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BienvenidoMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Bienvenido a EVA-WEB!',
            from: new \Illuminate\Mail\Mailables\Address(
                'noreply@eva-web.test',
                'Equipo EVA-WEB'
            ),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.bienvenido',
        );
    }
}
