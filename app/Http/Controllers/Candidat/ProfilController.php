<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    public function edit()
    {
        return view('candidat.profil', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'prenom' => 'required|string|max:100',
            'nom'    => 'required|string|max:100',
            'tel'    => 'nullable|string|max:20',
            'pays'   => 'nullable|string|max:100',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['prenom', 'nom', 'tel', 'pays']);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        Auth::user()->update($data);

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    public function parametres()
    {
        return view('candidat.parametres', ['user' => Auth::user()]);
    }

    public function updateParametres(Request $request)
    {
        $request->validate([
            'tel' => 'nullable|string|max:20',
        ]);

        Auth::user()->update($request->only(['tel']));

        return back()->with('success', 'Paramètres mis à jour.');
    }
}
