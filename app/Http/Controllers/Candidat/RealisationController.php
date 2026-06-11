<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\CandidatRealisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RealisationController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        abort_if($user->realisations()->count() >= CandidatRealisation::MAX_PAR_CANDIDAT, 422, 'Maximum 8 photos de réalisations.');

        $request->validate([
            'photos'         => 'required|array|max:8',
            'photos.*'       => 'image|mimes:jpg,jpeg,png,webp|max:3072',
            'titre'          => 'nullable|string|max:100',
            'descriptions'   => 'nullable|array',
            'descriptions.*' => 'nullable|string|max:300',
        ]);

        $ordre = $user->realisations()->max('ordre') ?? 0;

        foreach ($request->file('photos', []) as $i => $file) {
            if ($user->realisations()->count() >= CandidatRealisation::MAX_PAR_CANDIDAT) break;
            $user->realisations()->create([
                'titre'       => $request->input('titre') ?: null,
                'photo'       => $file->store('candidats/realisations', 'public'),
                'description' => $request->input("descriptions.$i") ?: null,
                'ordre'       => ++$ordre,
            ]);
        }

        return redirect()->route('candidat.profil')->with('success', 'Photos ajoutées avec succès.');
    }

    public function destroy(CandidatRealisation $realisation)
    {
        abort_unless($realisation->user_id === Auth::id(), 403);
        Storage::disk('public')->delete($realisation->photo);
        $realisation->delete();

        return redirect()->route('candidat.profil')->with('success', 'Photo supprimée.');
    }
}
