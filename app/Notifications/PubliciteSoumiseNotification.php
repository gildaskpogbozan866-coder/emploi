<?php

namespace App\Notifications;

use App\Models\Publicite;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PubliciteSoumiseNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public function __construct(public Publicite $publicite) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('✅ Votre publicité a bien été soumise — Emploi Bouge Bénin')
            ->greeting('Bonjour ' . $notifiable->prenom . ',')
            ->line('Votre paiement a été confirmé et votre publicité est maintenant soumise pour validation.')
            ->line('**Annonce :** ' . $this->publicite->titre)
            ->line('Notre équipe va examiner votre annonce dans les plus brefs délais (généralement sous 24h). Vous recevrez un email dès qu\'elle sera approuvée ou en cas de rejet.')
            ->action('Suivre mes annonces', route('annonceur.publicites'))
            ->salutation('L\'équipe Emploi Bouge Bénin');
    }
}
