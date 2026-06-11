<?php

namespace App\Notifications;

use App\Models\Offre;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NouvelleOffreCreee extends Notification
{
    public function __construct(public Offre $offre) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $offre      = $this->offre;
        $recruteur  = $offre->recruteur;
        $detailUrl  = route('admin.offres.detail', $offre);

        return (new MailMessage)
            ->subject("Nouvelle offre publiée — {$offre->titre}")
            ->greeting('Bonjour,')
            ->line("Une nouvelle offre d'emploi vient d'être publiée sur la plateforme.")
            ->line("**Titre :** {$offre->titre}")
            ->line("**Entreprise :** {$offre->entreprise}")
            ->line("**Type :** {$offre->type} · {$offre->localisation}")
            ->line("**Recruteur :** {$recruteur->prenom} {$recruteur->nom} ({$recruteur->email})")
            ->action("Voir l'offre dans l'admin", $detailUrl)
            ->salutation('Emploi Bouge Bénin — Système de notifications');
    }
}
