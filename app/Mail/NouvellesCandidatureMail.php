<?php

namespace App\Mail;

use App\Models\Candidature;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NouvellesCandidatureMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Candidature $candidature) {}

    public function envelope(): Envelope
    {
        $offreTitre = $this->candidature->offre?->titre ?? 'une offre';

        return new Envelope(
            subject: "Nouvelle candidature reçue — {$offreTitre}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.candidature-recruteur',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
