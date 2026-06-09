<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\CompetenceCandidat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompetenceController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom'    => 'required|string|max:100',
            'niveau' => 'required|in:debutant,intermediaire,avance,expert',
        ]);

        // Limite : 30 compétences max par candidat
        if (Auth::user()->competences()->count() >= 30) {
            return response()->json(['message' => 'Maximum 30 compétences atteint.'], 422);
        }

        // Pas de doublon (insensible à la casse)
        $existe = Auth::user()->competences()
            ->whereRaw('LOWER(nom) = ?', [strtolower($data['nom'])])
            ->exists();

        if ($existe) {
            return response()->json(['message' => 'Cette compétence existe déjà.'], 422);
        }

        $competence = CompetenceCandidat::create([
            'candidat_id' => Auth::id(),
            'nom'         => $data['nom'],
            'niveau'      => $data['niveau'],
        ]);

        return response()->json(['success' => true, 'competence' => $competence], 201);
    }

    public function destroy(CompetenceCandidat $competence)
    {
        abort_if($competence->candidat_id !== Auth::id(), 403);
        $competence->delete();

        return response()->json(['success' => true]);
    }
}
