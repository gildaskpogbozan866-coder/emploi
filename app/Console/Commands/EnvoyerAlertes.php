<?php

namespace App\Console\Commands;

use App\Services\AlerteService;
use Illuminate\Console\Command;

class EnvoyerAlertes extends Command
{
    protected $signature   = 'alertes:envoyer {frequence : quotidien ou hebdomadaire}';
    protected $description = 'Envoie les notifications d\'alertes emploi selon la fréquence choisie.';

    public function handle(AlerteService $service): int
    {
        $frequence = $this->argument('frequence');

        if (!in_array($frequence, ['quotidien', 'hebdomadaire'])) {
            $this->error('Fréquence invalide. Utilisez "quotidien" ou "hebdomadaire".');
            return self::FAILURE;
        }

        $nb = $service->notifierDigest($frequence);
        $this->info("{$nb} notification(s) envoyée(s) ({$frequence}).");

        return self::SUCCESS;
    }
}
