<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Models\TalentFormation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormationController extends Controller
{
    public function store(Request $request)
    {
        $profil = Auth::user()->talentProfil;
        abort_unless($profil, 404);

        $data = $request->validate([
            'diplome'         => 'required|string|max:200',
            'etablissement'   => 'nullable|string|max:200',
            'annee_obtention' => 'nullable|digits:4|integer|min:1950|max:' . (date('Y') + 1),
            'description'     => 'nullable|string|max:1000',
        ]);

        $profil->formations()->create($data);

        return redirect()->route('talent.profil.edit', ['#formations'])
            ->with('success', 'Formation ajoutée.');
    }

    public function destroy(TalentFormation $formation)
    {
        abort_unless($formation->profil->user_id === Auth::id(), 403);
        $formation->delete();

        return redirect()->route('talent.profil.edit', ['#formations'])
            ->with('success', 'Formation supprimée.');
    }
}
