<?php

namespace App\Notifications;

use App\Models\Offre;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlerteOffreNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Offre $offre,
        public readonly string $nomAlerte,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Nouvelle offre pour votre alerte « {$this->nomAlerte} »")
            ->greeting('Bonjour ' . $notifiable->prenom . ' !')
            ->line("Une offre correspondant à votre alerte **{$this->nomAlerte}** vient d'être publiée.")
            ->line("**{$this->offre->titre}** — {$this->offre->entreprise}, {$this->offre->localisation}")
            ->action('Voir l\'offre', route('offre.detail', $this->offre))
            ->line('Bonne chance dans votre recherche !')
            ->salutation('L\'équipe Emploi Bouge Bénin');
    }
}
