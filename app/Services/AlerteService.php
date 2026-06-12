<?php

namespace App\Services;

use App\Models\Alerte;
use App\Models\Notification;
use App\Models\Offre;
use App\Notifications\AlerteOffreNotification;

class AlerteService
{
    public function matcheOffre(Alerte $alerte, Offre $offre): bool
    {
        // Mots-clés : au moins un doit apparaître dans titre / description / compétences
        if ($alerte->mots_cles) {
            $mots     = array_filter(array_map('trim', preg_split('/[,\s]+/', $alerte->mots_cles)));
            $competenceNoms = $offre->relationLoaded('competences')
                ? $offre->competences->pluck('nom')->implode(' ')
                : $offre->competences()->pluck('nom')->implode(' ');
            $haystack = strtolower($offre->titre.' '.strip_tags($offre->description).' '.$competenceNoms);
            $trouve   = false;
            foreach ($mots as $mot) {
                if (str_contains($haystack, strtolower($mot))) {
                    $trouve = true;
                    break;
                }
            }
            if (!$trouve) return false;
        }

        // Localisation
        if ($alerte->localisation) {
            if (!str_contains(strtolower($offre->localisation), strtolower($alerte->localisation))) {
                return false;
            }
        }

        // Type de contrat
        if ($alerte->type_contrat && $alerte->type_contrat !== $offre->type) {
            return false;
        }

        // Secteur
        if ($alerte->secteur) {
            if (!str_contains(strtolower($offre->secteur ?? ''), strtolower($alerte->secteur))) {
                return false;
            }
        }

        return true;
    }

    public function notifierImmediat(Offre $offre): void
    {
        Alerte::where('active', true)
            ->where('frequence', 'immediat')
            ->with('user')
            ->chunkById(100, function ($alertes) use ($offre) {
                foreach ($alertes as $alerte) {
                    if ($this->matcheOffre($alerte, $offre)) {
                        $this->creerNotification($alerte, $offre);
                    }
                }
            });
    }

    public function notifierDigest(string $frequence): int
    {
        $depuis = match($frequence) {
            'quotidien'    => now()->subDay(),
            'hebdomadaire' => now()->subWeek(),
            default        => now()->subDay(),
        };

        $offres = Offre::where('statut', 'active')
            ->where('updated_at', '>=', $depuis)
            ->with('competences')
            ->get();

        if ($offres->isEmpty()) return 0;

        $count = 0;

        Alerte::where('active', true)
            ->where('frequence', $frequence)
            ->chunkById(100, function ($alertes) use ($offres, &$count) {
                foreach ($alertes as $alerte) {
                    foreach ($offres as $offre) {
                        if ($this->matcheOffre($alerte, $offre)) {
                            $created = $this->creerNotification($alerte, $offre);
                            if ($created) $count++;
                        }
                    }
                }
            });

        return $count;
    }

    private function creerNotification(Alerte $alerte, Offre $offre): bool
    {
        $lien = route('offre.detail', $offre);

        // Evite les doublons pour la même offre et le même candidat
        $existe = Notification::where('user_id', $alerte->user_id)
            ->where('type', 'alerte')
            ->where('lien', $lien)
            ->exists();

        if ($existe) return false;

        Notification::create([
            'user_id' => $alerte->user_id,
            'type'    => 'alerte',
            'titre'   => 'Nouvelle offre : '.$offre->titre,
            'contenu' => $offre->entreprise.' · '.$offre->localisation.' · '.$offre->type
                         .' — correspond à votre alerte « '.$alerte->nom.' »',
            'lien'    => $lien,
            'lu'      => false,
        ]);

        // Email au candidat (queued)
        if ($alerte->relationLoaded('user') && $alerte->user) {
            $alerte->user->notify(new AlerteOffreNotification($offre, $alerte->nom));
        }

        return true;
    }
}
