<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Models\TalentExperience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExperienceController extends Controller
{
    public function store(Request $request)
    {
        $profil = Auth::user()->talentProfil;
        abort_unless($profil, 404);

        $data = $request->validate([
            'poste'       => 'required|string|max:200',
            'employeur'   => 'nullable|string|max:200',
            'date_debut'  => 'required|string|max:20',
            'date_fin'    => 'nullable|string|max:20',
            'en_cours'    => 'nullable|boolean',
            'description' => 'nullable|string|max:1000',
        ]);

        $data['en_cours'] = $request->boolean('en_cours');
        if ($data['en_cours']) $data['date_fin'] = null;

        $profil->experiences()->create($data);

        return redirect()->route('talent.profil.edit', ['#experiences'])
            ->with('success', 'Expérience ajoutée.');
    }

    public function destroy(TalentExperience $experience)
    {
        abort_unless($experience->profil->user_id === Auth::id(), 403);
        $experience->delete();

        return redirect()->route('talent.profil.edit', ['#experiences'])
            ->with('success', 'Expérience supprimée.');
    }
}
