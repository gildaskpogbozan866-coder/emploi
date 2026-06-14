<?php

namespace App\Notifications;

use App\Models\Publicite;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PubliciteApprouveeNotification extends Notification
{
    public function __construct(public Publicite $publicite) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("✅ Votre annonce a été approuvée — Emploi Bouge Bénin")
            ->greeting("Bonjour {$notifiable->prenom},")
            ->line("Bonne nouvelle ! Votre annonce publicitaire a été examinée et **approuvée** par notre équipe.")
            ->line("**Annonce :** {$this->publicite->titre}")
            ->line("Elle est désormais visible sur la plateforme et sera affichée auprès des visiteurs.")
            ->action('Voir mes annonces', route('annonceur.publicites'))
            ->salutation('L\'équipe Emploi Bouge Bénin');
    }
}
