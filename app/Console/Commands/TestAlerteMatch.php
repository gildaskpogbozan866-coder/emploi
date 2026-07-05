<?php

namespace App\Console\Commands;

use App\Models\Alerte;
use App\Models\Offre;
use App\Services\AlerteService;
use Illuminate\Console\Command;

class TestAlerteMatch extends Command
{
    protected $signature   = 'test:alerte-match {offre_id?}';
    protected $description = 'Teste le matching alerte/offre (debug temporaire)';

    public function handle(): void
    {
        $alerte = Alerte::where('active', true)->where('frequence', 'immediat')->first();
        $offre  = $this->argument('offre_id')
            ? Offre::findOrFail($this->argument('offre_id'))
            : Offre::latest()->first();

        $this->line('Offre ID : ' . $offre?->id . ' | créée le : ' . $offre?->created_at);

        if (!$alerte) {
            $this->error('Aucune alerte active avec frequence=immediat trouvée.');
            return;
        }

        if (!$offre) {
            $this->error('Aucune offre trouvée.');
            return;
        }

        $this->info('ALERTE :');
        $this->line(json_encode($alerte->only(['metier','localisation','type_contrat','secteur','frequence']), JSON_PRETTY_PRINT));

        $this->info('OFFRE :');
        $this->line(json_encode($offre->only(['titre','metier','localisation','type','secteur']), JSON_PRETTY_PRINT));

        // Debug critère par critère
        $this->info('--- DEBUG CRITÈRES ---');

        // Métier
        if ($alerte->metier) {
            $haystack = strtolower(($offre->metier ?? '') . ' ' . $offre->titre);
            $needle   = strtolower($alerte->metier);
            $ok = str_contains($haystack, $needle);
            $this->line('Métier    : ' . ($ok ? '✓' : '✗') . "  cherche «{$needle}» dans «{$haystack}»");
        } else {
            $this->line('Métier    : ✓ (non filtré)');
        }

        // Localisation
        if ($alerte->localisation) {
            $ok = str_contains(strtolower($offre->localisation), strtolower($alerte->localisation));
            $this->line('Localisation : ' . ($ok ? '✓' : '✗') . "  «{$alerte->localisation}» dans «{$offre->localisation}»");
        } else {
            $this->line('Localisation : ✓ (non filtré)');
        }

        // Type contrat
        if ($alerte->type_contrat) {
            $ok = $alerte->type_contrat === $offre->type?->code;
            $this->line('Type contrat : ' . ($ok ? '✓' : '✗') . "  «{$alerte->type_contrat}» vs «{$offre->type?->code}»");
        } else {
            $this->line('Type contrat : ✓ (non filtré)');
        }

        // Secteur
        if ($alerte->secteur) {
            $secteursOffre = (array) ($offre->secteur ?? []);
            $found = false;
            foreach ($secteursOffre as $s) {
                if (str_contains(strtolower((string) $s), strtolower($alerte->secteur))) {
                    $found = true;
                    break;
                }
            }
            $this->line('Secteur   : ' . ($found ? '✓' : '✗') . "  cherche «{$alerte->secteur}» dans " . json_encode($secteursOffre));
        } else {
            $this->line('Secteur   : ✓ (non filtré)');
        }
    }
}
