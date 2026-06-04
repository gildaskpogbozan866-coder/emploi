<?php

namespace App\Http\Controllers\Recruteur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    public function edit()
    {
        return view('recruteur.profil', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'prenom'     => 'required|string|max:100',
            'nom'        => 'required|string|max:100',
            'entreprise' => 'nullable|string|max:200',
            'tel'        => 'nullable|string|max:20',
            'pays'       => 'nullable|string|max:100',
            'avatar'     => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['prenom','nom','entreprise','tel','pays']);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        Auth::user()->update($data);
        return back()->with('success', 'Profil mis à jour.');
    }

    public function parametres()
    {
        return view('recruteur.parametres', ['user' => Auth::user()]);
    }

    public function updateParametres(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'tel'   => 'nullable|string|max:20',
        ]);

        Auth::user()->update($request->only(['email','tel']));
        return back()->with('success', 'Paramètres mis à jour.');
    }
}
