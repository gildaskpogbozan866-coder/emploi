<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Models\TalentProfil;
use App\Models\TalentTravail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function show()
    {
        return redirect()->route('candidat.profil');
    }

    public function create()
    {
        // Le profil est créé automatiquement à l'inscription
        if (Auth::user()->talentProfil) {
            return redirect()->route('talent.profil.edit');
        }
        return view('talent.profil-create');
    }

    public function store(Request $request)
    {
        $request->merge(array_map(
            fn($v) => $v === '' ? null : $v,
            $request->only(['disponibilite', 'portfolio_url', 'specialite', 'ville', 'bio', 'experience', 'annees_experience'])
        ));

        $data = $request->validate([
            'metier'            => 'nullable|string|max:200',
            'specialite'        => 'nullable|string|max:200',
            'pays'              => 'nullable|string|max:100',
            'ville'             => 'nullable|string|max:100',
            'bio'               => 'nullable|string|max:2000',
            'competences'       => 'nullable|string',
            'annees_experience' => 'nullable|integer|min:0|max:50',
            'experience'        => 'nullable|string|max:500',
            'langues_json'      => 'nullable|string',
            'portfolio_url'     => 'nullable|url|max:500',
            'disponibilite'     => 'nullable|in:immediatement,1_mois,2_mois,3_mois,plus_3_mois',
            'types_contrat'     => 'nullable|array',
            'types_contrat.*'   => 'in:cdi,cdd,stage,alternance,interim',
            'photo'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data['user_id']       = Auth::id();
        $data['plan']          = 'gratuit';
        $data['visible']       = true;
        $data['competences']   = $this->parseCompetences($request->input('competences', ''));
        $data['langues']       = $this->parseLangues($request->input('langues_json', '[]'));
        $data['types_contrat'] = $request->input('types_contrat', []);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('talents/photos', 'public');
        }

        unset($data['langues_json']);

        TalentProfil::create($data);

        return redirect()->route('talent.profil')->with('success', 'Profil Talent créé avec succès !');
    }

    public function edit()
    {
        $profil = Auth::user()->talentProfil;
        if (!$profil) {
            return redirect()->route('talent.profil.create');
        }
        $profil->load('experiences', 'formations', 'attestations', 'travaux');
        return view('talent.profil-edit', compact('profil'));
    }

    public function update(Request $request)
    {
        $profil = Auth::user()->talentProfil;
        abort_unless($profil, 404);

        $request->merge(array_map(
            fn($v) => $v === '' ? null : $v,
            $request->only(['disponibilite', 'portfolio_url', 'specialite', 'ville', 'bio', 'experience', 'annees_experience'])
        ));

        $request->validate([
            'metier'            => 'required|string|max:200',
            'specialite'        => 'nullable|string|max:200',
            'pays'              => 'required|string|max:100',
            'ville'             => 'nullable|string|max:100',
            'bio'               => 'nullable|string|max:2000',
            'competences'       => 'nullable|string',
            'annees_experience' => 'nullable|integer|min:0|max:50',
            'experience'        => 'nullable|string|max:500',
            'langues_json'      => 'nullable|string',
            'portfolio_url'     => 'nullable|url|max:500',
            'disponibilite'     => 'nullable|in:immediatement,1_mois,2_mois,3_mois,plus_3_mois',
            'types_contrat'     => 'nullable|array',
            'types_contrat.*'   => 'in:cdi,cdd,stage,alternance,interim',
            'photo'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only([
            'metier', 'specialite', 'pays', 'ville', 'bio',
            'annees_experience', 'experience', 'portfolio_url', 'disponibilite',
        ]);

        $data['competences']   = $this->parseCompetences($request->input('competences', ''));
        $data['langues']       = $this->parseLangues($request->input('langues_json', '[]'));
        $data['types_contrat'] = $request->input('types_contrat', []);

        if ($request->hasFile('photo')) {
            if ($profil->photo) {
                Storage::disk('public')->delete($profil->photo);
            }
            $data['photo'] = $request->file('photo')->store('talents/photos', 'public');
        }

        $profil->update($data);

        return redirect()->route('talent.profil')->with('success', 'Profil mis à jour avec succès.');
    }

    public function deletePhoto()
    {
        $profil = Auth::user()->talentProfil;
        abort_unless($profil, 404);

        if ($profil->photo) {
            Storage::disk('public')->delete($profil->photo);
            $profil->update(['photo' => null]);
        }

        return redirect()->route('talent.profil')->with('success', 'Photo supprimée.');
    }

    public function storeTravail(Request $request)
    {
        $user   = Auth::user();
        $profil = $user->talentProfil ?? TalentProfil::create([
            'user_id' => $user->id, 'metier' => '', 'pays' => $user->pays ?? '',
            'plan' => 'gratuit', 'visible' => true,
        ]);

        abort_if($profil->travaux()->count() >= 8, 422, 'Maximum 8 photos de travaux.');

        $request->validate([
            'photos'          => 'required|array|max:8',
            'photos.*'        => 'image|mimes:jpg,jpeg,png,webp|max:3072',
            'descriptions'    => 'nullable|array',
            'descriptions.*'  => 'nullable|string|max:300',
        ]);

        foreach ($request->file('photos', []) as $i => $file) {
            if ($profil->travaux()->count() >= 8) break;
            $profil->travaux()->create([
                'photo'       => $file->store('talents/travaux', 'public'),
                'description' => $request->input("descriptions.$i") ?: null,
            ]);
        }

        return redirect()->route('candidat.profil')->with('success', 'Photos ajoutées avec succès.');
    }

    public function deleteTravail(TalentTravail $travail)
    {
        abort_unless($travail->profil->user_id === Auth::id(), 403);

        Storage::disk('public')->delete($travail->photo);
        $travail->delete();

        return redirect()->route('candidat.profil')->with('success', 'Photo supprimée.');
    }

    private function parseCompetences(?string $raw): array
    {
        if (!$raw) return [];
        return array_values(array_filter(
            array_map('trim', explode(',', $raw)),
            fn($c) => $c !== ''
        ));
    }

    private function parseLangues(?string $json): array
    {
        if (!$json) return [];
        $decoded = json_decode($json, true);
        if (!is_array($decoded)) return [];

        return array_values(array_filter($decoded, function ($item) {
            return isset($item['langue']) && trim($item['langue']) !== '';
        }));
    }
}
