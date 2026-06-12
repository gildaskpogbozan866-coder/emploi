<?php

namespace App\Notifications;

use App\Models\Candidature;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CandidatureStatutNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private const LABELS = [
        'envoyee'   => 'Envoyée',
        'vue'       => 'Vue par le recruteur',
        'retenue'   => 'Retenue ✓',
        'entretien' => 'Convoqué(e) en entretien',
        'refusee'   => 'Non retenue',
    ];

    public function __construct(
        public readonly Candidature $candidature,
        public readonly string $statut,
        public readonly ?string $noteRecruteur = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $offre  = $this->candidature->offre;
        $statut = self::LABELS[$this->statut] ?? ucfirst($this->statut);
        $url    = route('candidat.candidatures.detail', $this->candidature);

        $mail = (new MailMessage)
            ->subject("Mise à jour de votre candidature — {$offre->titre}")
            ->greeting('Bonjour ' . $notifiable->prenom . ' !')
            ->line("Le statut de votre candidature pour le poste **{$offre->titre}** chez **{$offre->entreprise}** a été mis à jour.")
            ->line("**Nouveau statut :** {$statut}");

        if ($this->noteRecruteur) {
            $mail->line("**Message du recruteur :** {$this->noteRecruteur}");
        }

        return $mail
            ->action('Voir ma candidature', $url)
            ->line('Connectez-vous à votre espace candidat pour suivre l\'évolution de toutes vos candidatures.')
            ->salutation('L\'équipe Emploi Bouge Bénin');
    }
}
