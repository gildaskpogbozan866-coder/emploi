<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParametreController extends Controller
{
    public function index()
    {
        return view('talent.parametres', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'prenom' => 'required|string|max:100',
            'nom'    => 'required|string|max:100',
            'tel'    => 'nullable|string|max:20',
            'pays'   => 'nullable|string|max:100',
            'metier' => 'nullable|string|max:200',
        ]);

        Auth::user()->update($request->only(['prenom', 'nom', 'tel', 'pays', 'metier']));
        return back()->with('success', 'Profil mis à jour avec succès.');
    }
}
