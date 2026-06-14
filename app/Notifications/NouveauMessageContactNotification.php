<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NouveauMessageContactNotification extends Notification
{
    public function __construct(public ContactMessage $message) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $sujetLabel = $this->message->sujet_label;
        $voirUrl    = route('admin.contact-messages.show', $this->message);

        return (new MailMessage)
            ->subject("Nouveau message de contact — {$sujetLabel}")
            ->greeting('Bonjour,')
            ->line("Un nouveau message a été reçu depuis le formulaire de contact.")
            ->line("**De :** {$this->message->prenom} {$this->message->nom} ({$this->message->email})")
            ->line("**Sujet :** {$sujetLabel}")
            ->line("**Message :**")
            ->line($this->message->message)
            ->action('Voir le message', $voirUrl)
            ->salutation('Emploi Bouge Bénin — Système de notifications');
    }
}
