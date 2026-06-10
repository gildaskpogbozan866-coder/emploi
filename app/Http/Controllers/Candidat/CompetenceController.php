<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Candidat\CompetenceRequest;
use App\Models\CompetenceCandidat;
use Illuminate\Support\Facades\Auth;

class CompetenceController extends Controller
{
    public function store(CompetenceRequest $request)
    {
        $user = Auth::user();

        if ($user->competences()->count() >= 30) {
            return response()->json(['message' => 'Maximum 30 compétences atteint.'], 422);
        }

        $existe = CompetenceCandidat::where('candidat_id', $user->id)
            ->where('competence_id', $request->competence_id)
            ->exists();

        if ($existe) {
            return response()->json(['message' => 'Cette compétence est déjà dans votre profil.'], 422);
        }

        $pivot = CompetenceCandidat::create([
            'candidat_id'      => $user->id,
            'competence_id'    => $request->competence_id,
            'annees_experience' => $request->annees_experience,
        ]);

        $pivot->load('competence');

        return response()->json([
            'success'    => true,
            'competence' => [
                'id'               => $pivot->id,
                'nom'              => $pivot->competence->nom,
                'annees_experience' => $pivot->annees_experience,
            ],
        ], 201);
    }

    public function destroy(CompetenceCandidat $competence)
    {
        abort_if($competence->candidat_id !== Auth::id(), 403);
        $competence->delete();

        return response()->json(['success' => true]);
    }
}
