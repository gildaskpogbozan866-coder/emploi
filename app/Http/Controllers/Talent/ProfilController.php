<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Models\TalentProfil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    public function show()
    {
        $profil = Auth::user()->talentProfil;
        return view('talent.profil', compact('profil'));
    }

    public function create()
    {
        if (Auth::user()->talentProfil) {
            return redirect()->route('talent.profil.edit');
        }
        return view('talent.profil-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'metier'      => 'required|string|max:200',
            'pays'        => 'required|string|max:100',
            'ville'       => 'nullable|string|max:100',
            'bio'         => 'nullable|string|max:1000',
            'competences' => 'nullable|string',
            'experience'  => 'nullable|string',
            'langues'     => 'nullable|string|max:200',
            'photo'       => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['metier','specialite','pays','ville','bio','competences','experience','langues','portfolio_url']);
        $data['user_id'] = Auth::id();
        $data['plan']    = 'gratuit';
        $data['visible'] = true;

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('talents/photos', 'public');
        }

        TalentProfil::create($data);

        return redirect()->route('talent.dashboard')->with('success', 'Profil Talent créé avec succès !');
    }

    public function edit()
    {
        $profil = Auth::user()->talentProfil;
        if (!$profil) {
            return redirect()->route('talent.profil.create');
        }
        return view('talent.profil-edit', compact('profil'));
    }

    public function update(Request $request)
    {
        $profil = Auth::user()->talentProfil;
        abort_unless($profil, 404);

        $request->validate([
            'metier' => 'required|string|max:200',
            'pays'   => 'required|string|max:100',
        ]);

        $data = $request->only(['metier','specialite','pays','ville','bio','competences','experience','langues','portfolio_url']);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('talents/photos', 'public');
        }

        $profil->update($data);

        return redirect()->route('talent.profil')->with('success', 'Profil mis à jour.');
    }
}
