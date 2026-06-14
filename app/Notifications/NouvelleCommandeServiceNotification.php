<?php

namespace App\Notifications;

use App\Models\Commande;
use App\Models\Paiement;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NouvelleCommandeServiceNotification extends Notification
{
    public function __construct(
        private Commande $commande,
        private Paiement $paiement,
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $montant  = number_format($this->paiement->montant, 0, ',', ' ') . ' XOF';
        $client   = $this->commande->user
            ? trim(($this->commande->user->prenom ?? '') . ' ' . ($this->commande->user->nom ?? ''))
            : 'Client inconnu';
        $service  = $this->commande->service?->nom ?? 'Service #' . $this->commande->service_id;
        $adminUrl = route('admin.commandes.detail', $this->commande);

        return (new MailMessage)
            ->subject("Nouvelle commande de service payée — {$service}")
            ->greeting('Bonjour,')
            ->line("Une commande de service vient d'être réglée et attend traitement.")
            ->line("**Client :** {$client}")
            ->line("**Service :** {$service}")
            ->line("**Référence :** {$this->commande->reference}")
            ->line("**Montant payé :** {$montant}")
            ->action('Voir la commande', $adminUrl)
            ->salutation('Emploi Bouge Bénin — Système de notifications');
    }
}
