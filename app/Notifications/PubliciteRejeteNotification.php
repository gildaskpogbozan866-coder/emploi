<?php

namespace App\Notifications;

use App\Models\Publicite;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PubliciteRejeteNotification extends Notification
{
    public function __construct(public Publicite $publicite, public string $motif) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("❌ Votre annonce n'a pas été approuvée — Emploi Bouge Bénin")
            ->greeting("Bonjour {$notifiable->prenom},")
            ->line("Après examen, votre annonce publicitaire n'a malheureusement pas pu être approuvée.")
            ->line("**Annonce :** {$this->publicite->titre}")
            ->line("**Motif :** {$this->motif}")
            ->line("Vous pouvez soumettre une nouvelle annonce en corrigeant les points mentionnés ci-dessus.")
            ->action('Soumettre une nouvelle annonce', route('annonceur.publicites'))
            ->salutation('L\'équipe Emploi Bouge Bénin');
    }
}
