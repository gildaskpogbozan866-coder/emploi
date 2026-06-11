<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\CandidatAttestation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttestationController extends Controller
{
    const MAX = 10;

    public function store(Request $request)
    {
        $user = Auth::user();
        abort_if($user->attestations()->count() >= self::MAX, 422, 'Maximum 10 attestations.');

        $request->validate([
            'nom'     => 'required|string|max:200',
            'fichier' => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
        ]);

        $user->attestations()->create([
            'nom'     => $request->input('nom'),
            'fichier' => $request->file('fichier')->store('candidats/attestations', 'public'),
        ]);

        return redirect()->route('candidat.profil')->with('success', 'Attestation ajoutée.');
    }

    public function destroy(CandidatAttestation $attestation)
    {
        abort_unless($attestation->user_id === Auth::id(), 403);
        Storage::disk('public')->delete($attestation->fichier);
        $attestation->delete();

        return redirect()->route('candidat.profil')->with('success', 'Attestation supprimée.');
    }
}
