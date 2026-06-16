<?php

namespace App\Notifications;

use App\Models\Candidature;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NouvellesCandidatureRecruteurNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private Candidature $candidature) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $offre    = $this->candidature->offre;
        $candidat = $this->candidature->candidat;
        $prenom   = $notifiable->prenom ?? 'Recruteur';

        $nomCandidat = trim(($candidat?->prenom ?? '') . ' ' . ($candidat?->nom ?? '')) ?: 'Un candidat';

        return (new MailMessage)
            ->subject("Nouvelle candidature reçue — {$offre->titre}")
            ->greeting("Bonjour {$prenom},")
            ->line("Vous avez reçu une nouvelle candidature pour votre offre **{$offre->titre}**.")
            ->line("**Candidat :** {$nomCandidat}")
            ->line("**Offre :** {$offre->titre} — {$offre->entreprise}")
            ->line("Connectez-vous à votre espace recruteur pour consulter le dossier complet du candidat et prendre une décision.")
            ->action('Voir la candidature', route('recruteur.candidatures.show', $this->candidature))
            ->line('Bonne sélection !')
            ->salutation("L'équipe Emploi Bouge Bénin");
    }
}
