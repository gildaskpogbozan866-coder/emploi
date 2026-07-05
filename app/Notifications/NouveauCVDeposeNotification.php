<?php

namespace App\Notifications;

use App\Models\CV;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NouveauCVDeposeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public CV $cv, public User $candidat) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $adminUrl = route('admin.cvs.detail', $this->cv);

        return (new MailMessage)
            ->subject('Nouveau CV déposé — ' . $this->cv->metier)
            ->greeting('Bonjour ' . ($notifiable->prenom ?? 'Admin') . ',')
            ->line('Un candidat vient de déposer un nouveau CV sur la plateforme.')
            ->line('**Candidat :** ' . $this->candidat->prenom . ' ' . $this->candidat->nom)
            ->line('**Email :** ' . $this->candidat->email)
            ->line('**Poste visé :** ' . $this->cv->metier)
            ->line('**Ville :** ' . ($this->cv->ville ?: '—'))
            ->action('Voir le CV dans l\'admin', $adminUrl)
            ->salutation('Emploi Bouge Bénin — Système de notifications');
    }
}
