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
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'tel'   => 'nullable|string|max:20',
        ]);

        Auth::user()->update($request->only(['email', 'tel']));
        return back()->with('success', 'Paramètres mis à jour.');
    }
}
