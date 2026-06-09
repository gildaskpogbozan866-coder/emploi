<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\CandidatProfil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function edit()
    {
        $user = Auth::user()->load([
            'candidatProfil',
            'experiences',
            'formations',
            'competences',
            'langues',
        ]);

        return view('candidat.profil', compact('user'));
    }

    // ── Infos personnelles + préférences ──────────────────
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'prenom'             => 'required|string|max:100',
            'nom'                => 'required|string|max:100',
            'tel'                => 'nullable|string|max:20',
            'pays'               => 'nullable|string|max:100',
            'avatar'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'titre_professionnel'=> 'nullable|string|max:200',
            'bio'                => 'nullable|string|max:1000',
            'ville'              => 'nullable|string|max:100',
            'disponibilite'      => 'nullable|in:immediatement,1_mois,2_mois,3_mois,plus_3_mois',
            'types_contrat'      => 'nullable|array',
            'types_contrat.*'    => 'in:cdi,cdd,freelance,stage,alternance',
            'salaire_min'        => 'nullable|integer|min:0|max:10000000',
            'salaire_max'        => 'nullable|integer|min:0|max:10000000|gte:salaire_min',
            'remote'             => 'nullable|in:non,partiel,total',
            'linkedin'           => 'nullable|url|max:500',
            'portfolio'          => 'nullable|url|max:500',
        ]);

        // Avatar
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update([
            'prenom' => $request->prenom,
            'nom'    => $request->nom,
            'tel'    => $request->tel,
            'pays'   => $request->pays,
            'avatar' => $user->avatar,
        ]);

        // Upsert profil étendu
        CandidatProfil::updateOrCreate(
            ['user_id' => $user->id],
            [
                'titre_professionnel' => $request->titre_professionnel,
                'bio'                 => $request->bio,
                'ville'               => $request->ville,
                'disponibilite'       => $request->disponibilite,
                'types_contrat'       => $request->types_contrat ?? [],
                'salaire_min'         => $request->salaire_min,
                'salaire_max'         => $request->salaire_max,
                'remote'              => $request->remote ?? 'non',
                'linkedin'            => $request->linkedin,
                'portfolio'           => $request->portfolio,
            ]
        );

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
