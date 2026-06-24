<?php

namespace App\Console\Commands;

use App\Models\CV;
use App\Models\User;
use Illuminate\Console\Command;

class SyncCvsFromProfils extends Command
{
    protected $signature   = 'app:sync-cvs';
    protected $description = 'Re-synchronise les champs texte de chaque CV depuis les données relationnelles du profil.';

    public function handle(): void
    {
        $candidats = User::where('role', 'candidat')
            ->whereHas('cvs')
            ->with([
                'candidatProfil',
                'metiers',
                'competences',
                'experiences'  => fn($q) => $q->orderByDesc('en_cours')->orderByDesc('date_debut'),
                'formations'   => fn($q) => $q->orderByDesc('en_cours')->orderByDesc('date_debut'),
                'languesCandidats.langue',
                'languesCandidats.niveau',
                'niveauEtude.niveauEtude',
                'niveauExperience.niveauExperience',
                'typesContrats',
                'cvs',
            ])->get();

        $bar = $this->output->createProgressBar($candidats->count());
        $bar->start();

        foreach ($candidats as $user) {
            $cv = $user->cvs->first();
            if (!$cv) { $bar->advance(); continue; }

            $profil  = $user->candidatProfil;
            $metier  = $user->metiers->pluck('nom')->join(', ');
            $resume  = $profil?->bio ?? '';
            $ville   = $profil?->ville ?? $user->pays ?? '';

            $competences = $user->competences->pluck('nom')->join(', ');

            $experience = $user->experiences->map(function ($exp) {
                $debut  = $exp->date_debut ? date('Y', strtotime($exp->date_debut)) : '';
                $fin    = $exp->en_cours ? 'présent' : ($exp->date_fin ? date('Y', strtotime($exp->date_fin)) : '');
                $annees = ($debut || $fin) ? " ($debut" . ($fin ? " – $fin" : '') . ')' : '';
                return trim(($exp->poste ?? '') . ($exp->entreprise ? ' — ' . $exp->entreprise : '') . $annees);
            })->filter()->join("\n");

            $formation = $user->formations->map(function ($f) {
                $annee = $f->date_fin
                    ? date('Y', strtotime($f->date_fin))
                    : ($f->date_debut ? date('Y', strtotime($f->date_debut)) : '');
                return trim(($f->diplome ?? '') . ($f->etablissement ? ' — ' . $f->etablissement : '') . ($annee ? " ($annee)" : ''));
            })->filter()->join("\n");

            $langues     = $user->languesCandidats->map(function ($lc) {
                $niv = $lc->niveau?->libelle ?? $lc->niveau?->code ?? '';
                return $lc->langue->nom . ($niv ? " ($niv)" : '');
            })->join(', ');

            $niveauEtude = $user->niveauEtude?->niveauEtude?->libelle ?? '';
            $niveauExp   = $user->niveauExperience?->niveauExperience?->libelle ?? '';
            $typeContrat = $user->typesContrats->pluck('libelle')->join(', ');
            $photo       = $cv->photo ?: $user->avatar;

            $cv->update([
                'metier'            => $metier ?: null,
                'resume'            => $resume ?: null,
                'ville'             => $ville ?: null,
                'competences'       => $competences ?: null,
                'experience'        => $experience ?: null,
                'formation'         => $formation ?: null,
                'langues'           => $langues ?: null,
                'niveau_etude'      => $niveauEtude ?: null,
                'niveau_experience' => $niveauExp ?: null,
                'type_contrat'      => $typeContrat ?: null,
                'photo'             => $photo ?: null,
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✓ {$candidats->count()} CV(s) synchronisé(s).");
    }
}
