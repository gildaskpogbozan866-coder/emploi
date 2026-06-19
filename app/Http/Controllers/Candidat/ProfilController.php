<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Candidat\ProfilRequest;
use App\Models\CandidatProfil;
use App\Models\Competence;
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
            'metiersCompetencesJson'
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
            ->with('success', 'Profil mis à jour avec succès.');

    } catch (\Throwable $e) {

        // Affiche l'erreur complète en développement
        dd([
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
        ]);

        // En production :
        // Log::error($e);
        // return back()->withErrors($e->getMessage());
    }
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
}
