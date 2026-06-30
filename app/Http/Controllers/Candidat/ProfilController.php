<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Candidat\ProfilRequest;
use App\Models\CandidatProfil;
use App\Models\Competence;
use App\Models\CV;
use App\Models\Langue;
use App\Models\Metier;
use App\Models\NiveauLangue;
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
            // Métiers ciblés (pour le filtre compétences)
            'metiers',
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
        ] = [
            Langue::orderBy('nom')->whereNotIn('id', $languesDejaAjoutees)->get(),
            NiveauLangue::orderBy('ordre')->get(),
            Competence::orderBy('nom')->get(),
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
                ]
            );
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
            $cv = CV::create(['candidat_id' => $user->id, 'plan' => 'gratuit', 'visible' => false]);
        }

        // Supprimer l'ancien fichier
        if ($cv->fichier_path && Storage::disk('public')->exists($cv->fichier_path)) {
            Storage::disk('public')->delete($cv->fichier_path);
        }

        $path = $request->file('fichier_cv')->store('cvs', 'public');
        $cv->update(['fichier_path' => $path]);

        return redirect()
            ->route('candidat.profil')
            ->with('success', 'Votre CV a été enregistré dans votre profil.');
    }

}
