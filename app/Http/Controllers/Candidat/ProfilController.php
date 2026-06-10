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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function edit()
    {
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
        ]);

        $languesCandidats = $user->languesCandidats;

        // ── Référentiels pour les dropdowns / pickers ──────────
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
            Langue::orderBy('nom')->get(),
            NiveauLangue::orderBy('ordre')->get(),
            Competence::orderBy('nom')->get(),
            Metier::orderBy('nom')->get(),
            NiveauEtude::orderBy('ordre')->get(),
            NiveauExperience::orderBy('ordre')->get(),
            TypeContrat::orderBy('libelle')->get(),
            SecteurActivite::orderBy('libelle')->get(),
        ];

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
        ));
    }

    // ── Infos personnelles + préférences ──────────────────
    public function update(ProfilRequest $request)
    {
        $user = Auth::user();

        // ── Avatar ────────────────────────────────────────────
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        // ── Identité ──────────────────────────────────────────
        $user->update([
            'prenom' => $request->prenom,
            'nom'    => $request->nom,
            'tel'    => $request->tel,
            'pays'   => $request->pays,
            'avatar' => $user->avatar,
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
            ]
        );

        // ── Pivots many-to-many (sync remplace tout) ──────────
        $user->typesContrats()->sync($request->input('types_contrat_ids', []));
        $user->secteursActivite()->sync($request->input('secteurs_ids', []));
        $user->metiers()->sync($request->input('metiers_ids', []));

        // ── Niveau d'étude (scalaire, 1 seul par candidat) ───
        if ($request->filled('niveau_etude_id')) {
            NiveauEtudeCandidat::updateOrCreate(
                ['candidat_id'    => $user->id],
                ['niveau_etude_id' => $request->niveau_etude_id]
            );
        } else {
            NiveauEtudeCandidat::where('candidat_id', $user->id)->delete();
        }

        // ── Niveau d'expérience (scalaire, 1 seul par candidat)
        if ($request->filled('niveau_experience_id')) {
            NiveauExperienceCandidat::updateOrCreate(
                ['candidat_id'         => $user->id],
                ['niveau_experience_id' => $request->niveau_experience_id]
            );
        } else {
            NiveauExperienceCandidat::where('candidat_id', $user->id)->delete();
        }

        return redirect()->route('candidat.profil')->with('success', 'Profil mis à jour avec succès.');
    }

    // ── Suppression d'avatar ──────────────────────────────
    public function deleteAvatar()
    {
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
