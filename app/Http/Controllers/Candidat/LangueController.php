<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Candidat\LangueRequest;
use App\Models\LangueCandidat;
use Illuminate\Support\Facades\Auth;

class LangueController extends Controller
{
    public function store(LangueRequest $request)
    {
        $data = $request->all();
      

        if (Auth::user()->langues()->count() >= 10) {
            return response()->json(['message' => 'Maximum 10 langues atteint.'], 422);
        }

        // Pas de doublon (unicité par langue pour un candidat, indépendant du niveau)
        $existe = LangueCandidat::where('candidat_id', Auth::id())
            ->where('langue_id', $data['langue_id'])
            ->exists();

        if ($existe) {
            return response()->json(['message' => 'Cette langue existe déjà.'], 422);
        }

        $langue = LangueCandidat::create([
            'candidat_id' => Auth::id(),
            'langue_id'   => $data['langue_id'],
            'niveau_id'   => $data['niveau_id'],
        ]);

        $langue->load('langue', 'niveau');

        return response()->json([
            'success' => true,
            'langue'  => [
                'id'        => $langue->id,
                'langue_id' => $langue->langue_id,
                'langue'    => $langue->langue->nom,
                'niveau'    => $langue->niveau->code,
            ],
        ], 201);
    }

    public function destroy(LangueCandidat $langue)
    {
        abort_if($langue->candidat_id !== Auth::id(), 403);
        $langue->delete();

        return response()->json(['success' => true]);
    }
}
