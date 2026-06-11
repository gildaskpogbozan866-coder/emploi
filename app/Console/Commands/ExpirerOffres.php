<?php

namespace App\Console\Commands;

use App\Models\Offre;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ExpirerOffres extends Command
{
    protected $signature   = 'offres:expirer';
    protected $description = 'Passe en "expiree" toutes les offres actives dont la date limite est dépassée.';

    public function handle(): int
    {
        $nb = Offre::where('statut', 'active')
            ->whereNotNull('date_limite')
            ->whereDate('date_limite', '<', Carbon::today())
            ->update(['statut' => 'expiree']);

        $this->info("{$nb} offre(s) expirée(s).");

        return self::SUCCESS;
    }
}
