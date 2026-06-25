<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Candidat\ProfilRequest;
use App\Models\CandidatProfil;
use App\Models\Competence;
use App\Models\CV;
use App\Models\Langue;
use App\Models\Metier;
use App\Models\NiveauEtude;
use App\Models\NiveauEtudeCandidat;
use App\Models\NiveauExperience;
use App\Models\NiveauExperienceCandidat;
use App\Models\NiveauLangue;
use App\Models\SecteurActivite;
use App\Models\TypeContrat;
use App\Models\TypeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user()->load([
            // Profil de base
            'candidatProfil',
            // Parcours (timeline)
            'experiences',
            'formations',
            // Compétences (pivot avec annees_experience)
            'competences',
            // Métiers ciblés
            'metiers',
            // Niveau d'étude actuel (HasOne → BelongsTo)
            'niveauEtude.niveauEtude',
            // Niveau d'expérience global (HasOne → BelongsTo)
            'niveauExperience.niveauExperience',
            // Types de contrats souhaités
            'typesContrats',
            // Secteurs d'activité ciblés
            'secteursActivite',
            // Langues (pour profilCompletion)
            'langues',
            // Langues candidat avec référentiels (affichage liste)
            'languesCandidats.langue',
            'languesCandidats.niveau',
            // Attestations & réalisations
            'attestations',
            'realisations',
            // Documents typés
            'documents.type',
        ]);

        $languesCandidats = $user->languesCandidats;

        // ── Référentiels pour les dropdowns / pickers ──────────
        $languesDejaAjoutees = $languesCandidats->pluck('langue_id');
        [
            $langues,
            $niveauxLangue,
            $competences,
            $metiers,
            $niveauxEtude,
            $niveauxExperience,
            $typesContrats,
            $secteursActivite,
        ] = [
            Langue::orderBy('nom')->whereNotIn('id', $languesDejaAjoutees)->get(),
            NiveauLangue::orderBy('ordre')->get(),
            Competence::orderBy('nom')->get(),
            Metier::orderBy('nom')->get(),
            NiveauEtude::orderBy('ordre')->get(),
            NiveauExperience::orderBy('ordre')->get(),
            TypeContrat::orderBy('libelle')->get(),
            SecteurActivite::orderBy('libelle')->get(),
        ];



        $typesDocuments = TypeDocument::actif()->get();
        $metiers = Metier::orderBy('nom')
            ->with('competences:id,nom,slug')
            ->get();

        // Compétences du candidat déjà sélectionnées (pour pré-cocher)
        $candidatCompetenceIds = $user->competences->pluck('id')->toArray();

        // JSON pour le JS (métier_id → compétences)
        $metiersCompetencesJson = $metiers->mapWithKeys(fn ($m) => [
            $m->id => $m->competences->map(fn ($c) => [
                'id'  => $c->id,
                'nom' => $c->nom,
            ])->values(),
        ]);

        // CV du candidat (pour afficher le statut de visibilité)
        $cv = $user->cvs()->first();

        $abonnement  = $user->abonnementActif()->with('plan')->first();
        $estPremium  = $abonnement && !($abonnement->plan?->is_free ?? true);

        return view('candidat.profil', compact(
            'user',
            'languesCandidats',
            'langues',
            'niveauxLangue',
            'competences',
            'metiers',
            'niveauxEtude',
            'niveauxExperience',
            'typesContrats',
            'secteursActivite',
            'typesDocuments',
            'candidatCompetenceIds',
            'metiersCompetencesJson',
            'cv',
            'estPremium'
        ));
    }

    // ── Infos personnelles + préférences ──────────────────
    public function update(ProfilRequest $request)
{
    /** @var \App\Models\User $user */
    $user = Auth::user();

    try {
        DB::transaction(function () use ($request, $user) {

            // ── Identité ──────────────────────────────────────────
            $user->update([
                'prenom' => $request->prenom,
                'nom'    => $request->nom,
                'tel'    => $request->tel,
                'pays'   => $request->pays,
            ]);

            // ── Profil étendu ─────────────────────────────────────
            CandidatProfil::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'titre_professionnel' => $request->titre_professionnel,
                    'bio'                 => $request->bio,
                    'ville'               => $request->ville,
                    'disponibilite'       => $request->disponibilite,
                    'salaire_min'         => $request->salaire_min,
                    'salaire_max'         => $request->salaire_max,
                    'remote'              => $request->remote ?? 'non',
                    'linkedin'            => $request->linkedin,
                    'portfolio'           => $request->portfolio,
                    'specialite'          => $request->specialite,
                    'annees_experience'   => $request->annees_experience,
                ]
            );

            // ── Pivots many-to-many ───────────────────────────────
            if ($request->has('types_contrat_ids')) {
                $user->typesContrats()->sync(
                    $request->input('types_contrat_ids', [])
                );
            }

            if ($request->has('secteurs_ids')) {
                $user->secteursActivite()->sync(
                    $request->input('secteurs_ids', [])
                );
            }

            if ($request->has('metiers_ids')) {
                $user->metiers()->sync(
                    $request->input('metiers_ids', [])
                );
            }

            // ── Niveau d'étude ────────────────────────────────────
            if ($request->filled('niveau_etude_id')) {
                NiveauEtudeCandidat::updateOrCreate(
                    ['candidat_id' => $user->id],
                    ['niveau_etude_id' => $request->niveau_etude_id]
                );
            } else {
                NiveauEtudeCandidat::where(
                    'candidat_id',
                    $user->id
                )->delete();
            }

            // ── Niveau d'expérience ───────────────────────────────
            if ($request->filled('niveau_experience_id')) {
                NiveauExperienceCandidat::updateOrCreate(
                    ['candidat_id' => $user->id],
                    ['niveau_experience_id' => $request->niveau_experience_id]
                );
            } else {
                NiveauExperienceCandidat::where(
                    'candidat_id',
                    $user->id
                )->delete();
            }
        });

        return redirect()
            ->route('candidat.profil')
            ->with('success', 'Profil mis à jour.');

    } catch (\Throwable $e) {
        dd([
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
        ]);
    }
}

    // ── Publication du CV dans la CVthèque ───────────────────
    public function publier(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user()->load([
            'candidatProfil',
            'competences',
            'experiences',
            'formations',
            'languesCandidats',
            'typesContrats',
            'cvs',
            'documents',
        ]);

        $profil  = $user->candidatProfil;
        $manque  = [];

        $aFichier = ($user->cvs->first()?->fichier_path) || $user->documents->isNotEmpty();

        if (empty(trim($profil?->bio ?? '')))          $manque[] = 'un résumé / bio';
        if (empty(trim($profil?->ville ?? '')))         $manque[] = 'votre ville';
        if (empty($profil?->disponibilite))             $manque[] = 'votre disponibilité';
        if ($user->typesContrats->isEmpty())            $manque[] = 'au moins un type de contrat souhaité';
        if ($user->competences->isEmpty())              $manque[] = 'au moins une compétence';
        if ($user->experiences->isEmpty() && $user->formations->isEmpty())
                                                        $manque[] = 'au moins une expérience ou une formation';
        if ($user->languesCandidats->isEmpty())         $manque[] = 'au moins une langue';
        if (!$aFichier)                                 $manque[] = 'un CV ou document téléversé (PDF / Word / diplôme…)';

        if (!empty($manque)) {
            return back()->with('publier_erreurs', $manque);
        }

        $cv = $user->cvs()->first();
        if (!$cv) {
            $cv = CV::create(['candidat_id' => $user->id, 'plan' => 'gratuit', 'visible' => false]);
        }

        $this->syncCvFromProfil($user, $cv);

        $estNouveau = is_null($cv->publie_le);

        $cv->update([
            'visible'   => true,
            'publie_le' => $cv->publie_le ?? now(),
            'vu_admin'  => false,
        ]);

        // Notifier tous les admins si c'est une première publication
        if ($estNouveau) {
            \App\Models\User::where('role', 'admin')->each(function ($admin) use ($user, $cv) {
                \App\Models\Notification::create([
                    'user_id' => $admin->id,
                    'type'    => 'nouveau_cv',
                    'titre'   => 'Nouveau CV déposé',
                    'contenu' => $user->nom_complet . ' a publié son profil dans la CVthèque.',
                    'lien'    => route('admin.cvs.detail', $cv),
                    'lu'      => false,
                ]);
            });
        }

        return redirect()
            ->route('candidat.profil')
            ->with('profil_publie', $cv->id);
    }

    // ── Upload avatar AJAX ────────────────────────────────
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (
            $user->avatar &&
            Storage::disk('public')->exists($user->avatar)
        ) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return response()->json(['url' => Storage::url($path)]);
    }

    // ── Suppression d'avatar ──────────────────────────────
    public function deleteAvatar()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
        }
        return redirect()->route('candidat.profil')->with('success', 'Photo supprimée.');
    }

    // ── Paramètres compte ─────────────────────────────────
    public function parametres()
    {
        return view('candidat.parametres', ['user' => Auth::user()]);
    }

    public function updateParametres(Request $request)
    {
        $request->validate(['tel' => 'nullable|string|max:20']);
        Auth::user()->update($request->only(['tel']));
        return back()->with('success', 'Paramètres mis à jour.');
    }

    // ── Upload fichier CV (PDF / Word / Image) ───────────────
    public function updateFichierCv(Request $request)
    {
        $request->validate([
            'fichier_cv' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,webp|max:5120',
        ], [
            'fichier_cv.required' => 'Veuillez sélectionner un fichier.',
            'fichier_cv.mimes'    => 'Format accepté : PDF, Word, JPG, PNG, WebP.',
            'fichier_cv.max'      => 'Le fichier ne doit pas dépasser 5 Mo.',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $cv = $user->cvs()->first();
        if (!$cv) {
            $cv = CV::create(['candidat_id' => $user->id, 'plan' => 'gratuit', 'visible' => true]);
        }

        // Supprimer l'ancien fichier
        if ($cv->fichier_path && Storage::disk('public')->exists($cv->fichier_path)) {
            Storage::disk('public')->delete($cv->fichier_path);
        }

        $path = $request->file('fichier_cv')->store('cvs', 'public');
        $cv->update(['fichier_path' => $path, 'visible' => true]);

        return redirect()
            ->route('candidat.profil', ['#section-upload-cv'])
            ->with('success', 'Votre CV a été mis en ligne avec succès. Les recruteurs peuvent maintenant le télécharger.');
    }

    // ── Synchronise toutes les données du profil vers le CV ──
    private function syncCvFromProfil(\App\Models\User $user, CV $cv): void
    {
        $user->load([
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
        ]);

        $profil = $user->candidatProfil;

        // Métier(s) ciblé(s)
        $metier = $user->metiers->pluck('nom')->join(', ');

        // Résumé / bio
        $resume = $profil?->bio ?? '';

        // Ville
        $ville = $profil?->ville ?? $user->pays ?? '';

        // Compétences
        $competences = $user->competences->pluck('nom')->join(', ');

        // Expériences (une ligne par poste)
        $experience = $user->experiences->map(function ($exp) {
            $debut = $exp->date_debut ? date('Y', strtotime($exp->date_debut)) : '';
            $fin   = $exp->en_cours ? 'présent' : ($exp->date_fin ? date('Y', strtotime($exp->date_fin)) : '');
            $annees = ($debut || $fin) ? " ($debut" . ($fin ? " – $fin" : '') . ')' : '';
            return trim(($exp->poste ?? '') . ($exp->entreprise ? ' — ' . $exp->entreprise : '') . $annees);
        })->filter()->join("\n");

        // Formations (une ligne par diplôme)
        $formation = $user->formations->map(function ($f) {
            $annee = $f->date_fin
                ? date('Y', strtotime($f->date_fin))
                : ($f->date_debut ? date('Y', strtotime($f->date_debut)) : '');
            return trim(($f->diplome ?? '') . ($f->etablissement ? ' — ' . $f->etablissement : '') . ($annee ? " ($annee)" : ''));
        })->filter()->join("\n");

        // Langues avec niveau
        $langues = $user->languesCandidats->map(function ($lc) {
            $niv = $lc->niveau?->libelle ?? $lc->niveau?->code ?? '';
            return $lc->langue->nom . ($niv ? " ($niv)" : '');
        })->join(', ');

        // Niveau d'étude
        $niveauEtude = $user->niveauEtude?->niveauEtude?->libelle ?? '';

        // Niveau d'expérience
        $niveauExp = $user->niveauExperience?->niveauExperience?->libelle ?? '';

        // Types de contrat
        $typeContrat = $user->typesContrats->pluck('libelle')->join(', ');

        // Photo : avatar du candidat si le CV n'a pas sa propre photo
        $photo = $cv->photo ?: $user->avatar;

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
    }
}
