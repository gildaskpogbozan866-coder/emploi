<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\LangueCandidat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LangueController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'langue' => 'required|string|max:80',
            'niveau' => 'required|in:A1,A2,B1,B2,C1,C2,natif',
        ]);

        // Limite : 10 langues max
        if (Auth::user()->langues()->count() >= 10) {
            return response()->json(['message' => 'Maximum 10 langues atteint.'], 422);
        }

        // Pas de doublon
        $existe = Auth::user()->langues()
            ->whereRaw('LOWER(langue) = ?', [strtolower($data['langue'])])
            ->exists();

        if ($existe) {
            return response()->json(['message' => 'Cette langue existe déjà.'], 422);
        }

        $langue = LangueCandidat::create([
            'candidat_id' => Auth::id(),
            'langue'      => $data['langue'],
            'niveau'      => $data['niveau'],
        ]);

        return response()->json(['success' => true, 'langue' => $langue], 201);
    }

    public function destroy(LangueCandidat $langue)
    {
        abort_if($langue->candidat_id !== Auth::id(), 403);
        $langue->delete();

        return response()->json(['success' => true]);
    }
}
