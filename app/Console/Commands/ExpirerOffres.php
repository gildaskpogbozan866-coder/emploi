<?php

namespace App\Console\Commands;

use App\Models\Offre;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ExpirerOffres extends Command
{
    protected $signature   = 'offres:expirer';
    protected $description = 'Passe en "expiree" les offres actives dont la date limite est dépassée, ou dont le recruteur n\'a plus d\'abonnement actif.';

    public function handle(): int
    {
        $nbDateLimite = Offre::where('statut', 'active')
            ->whereNotNull('date_limite')
            ->whereDate('date_limite', '<', Carbon::today())
            ->update(['statut' => 'expiree']);

        // Un recruteur qui a déjà eu un abonnement mais n'en a plus aucun d'actif ne doit
        // plus garder ses offres visibles, même si leur date_limite n'est pas dépassée.
        // On exclut les recruteurs sans aucun abonnement (offres admin/legacy) pour ne pas
        // toucher à des données hors du système d'abonnement.
        $nbAbonnementExpire = Offre::where('statut', 'active')
            ->whereHas('recruteur.abonnements')
            ->whereDoesntHave('recruteur.abonnements', fn ($q) => $q->actif())
            ->update(['statut' => 'expiree']);

        $this->info("{$nbDateLimite} offre(s) expirée(s) (date limite dépassée), {$nbAbonnementExpire} offre(s) expirée(s) (abonnement expiré).");

        return self::SUCCESS;
    }
}
