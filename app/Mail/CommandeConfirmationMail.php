<?php

namespace App\Mail;

use App\Models\Commande;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CommandeConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Commande $commande,
        public array $clientInfo,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Votre commande est confirmée — ' . $this->commande->reference,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.commande-confirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
