<?php

namespace App\Notifications;

use App\Enums\Role;
use App\Models\Abonnement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AbonnementActiveNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private Abonnement $abonnement) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $abonnement = $this->abonnement;
        $plan       = $abonnement->plan;
        $prenom     = $notifiable->prenom ?? 'Utilisateur';
        $isRecruteur = $notifiable->hasRole(Role::RECRUTEUR);
        $isAnnonceur = $notifiable->hasRole(Role::ANNONCEUR);

        $typeLabel  = match (true) {
            $isRecruteur => 'recruteur',
            $isAnnonceur => 'annonceur',
            default      => 'candidat',
        };
        $dashRoute  = match (true) {
            $isRecruteur => route('recruteur.abonnement'),
            $isAnnonceur => route('annonceur.dashboard'),
            default      => route('candidat.abonnement'),
        };

        $finLabel = $abonnement->ends_at
            ? $abonnement->ends_at->format('d/m/Y')
            : 'Sans limite de durée';

        $mail = (new MailMessage)
            ->subject("🎉 Votre abonnement {$typeLabel} est activé — Emploi Bouge Bénin")
            ->greeting("Bonjour {$prenom},")
            ->line("Votre abonnement **{$plan->name}** vient d'être activé avec succès.")
            ->line("**Date d'activation :** " . $abonnement->starts_at->format('d/m/Y'))
            ->line("**Valable jusqu'au :** {$finLabel}");

        // Lister les fonctionnalités du plan si disponibles — mêmes libellés et même
        // logique d'affichage que les pages de plans (recruteur/candidat.abonnement-plans) :
        // un simple checklist par libellé humain, sans jamais exposer la clé brute en base
        // ni une valeur "0" pour une fonctionnalité booléenne désactivée.
        $labels = $this->featureLabels($isRecruteur);
        $lignes = $plan->features
            ->map(function ($feature) use ($labels) {
                $def = $labels[$feature->feature_key] ?? [
                    'label' => ucfirst(str_replace('_', ' ', $feature->feature_key)),
                    'bool'  => false,
                ];
                if ($def['bool'] && !$feature->feature_value) {
                    return null;
                }
                return $def['label'];
            })
            ->filter();

        if ($lignes->isNotEmpty()) {
            $mail->line('---')
                 ->line('**Ce que votre plan inclut :**');
            foreach ($lignes as $ligne) {
                $mail->line("• {$ligne}");
            }
        }

        return $mail
            ->line('---')
            ->action('Gérer mon abonnement', $dashRoute)
            ->line("Pour toute question, n'hésitez pas à nous contacter.")
            ->salutation("L'équipe Emploi Bouge Bénin");
    }

    /** Mêmes libellés que recruteur/candidat.abonnement-plans — 'cv_limit' a un sens différent selon le rôle. */
    private function featureLabels(bool $isRecruteur): array
    {
        return $isRecruteur ? [
            'job_post_limit'   => ['label' => 'Offres publiables',        'bool' => false],
            'candidate_search' => ['label' => 'Accès à la CVthèque',       'bool' => true],
            'featured_jobs'    => ['label' => 'Offres mises en avant',     'bool' => false],
            'job_apply_limit'  => ['label' => 'Candidatures max / offre',  'bool' => false],
            'featured_profile' => ['label' => 'Profil mis en avant',       'bool' => true],
            'cv_limit'         => ['label' => 'CVs consultables',          'bool' => false],
        ] : [
            'cv_limit'           => ['label' => 'CVs publiables',                     'bool' => false],
            'job_apply_limit'    => ['label' => 'Candidatures / offre',                'bool' => false],
            'alert_limit'        => ['label' => 'Alertes emploi',                      'bool' => false],
            'featured_profile'   => ['label' => 'Profil mis en avant',                 'bool' => true],
            'candidate_search'   => ['label' => 'Accès CVthèque recruteurs',           'bool' => true],
            'show_profile_views' => ['label' => 'Voir qui a consulté votre profil',    'bool' => true],
            'job_post_limit'     => ['label' => 'Offres publiables',                   'bool' => false],
            'featured_jobs'      => ['label' => 'Offres mises en avant',               'bool' => false],
        ];
    }
}
