<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Models\TalentAttestation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttestationController extends Controller
{
    public function store(Request $request)
    {
        $user   = Auth::user();
        $profil = $user->talentProfil ?? \App\Models\TalentProfil::create([
            'user_id' => $user->id, 'metier' => '', 'pays' => $user->pays ?? '',
            'plan' => 'gratuit', 'visible' => true,
        ]);
        abort_if($profil->attestations()->count() >= 10, 422, 'Maximum 10 attestations.');

        $request->validate([
            'nom'     => 'required|string|max:200',
            'fichier' => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
        ]);

        $profil->attestations()->create([
            'nom'     => $request->input('nom'),
            'fichier' => $request->file('fichier')->store('talents/attestations', 'public'),
        ]);

        return redirect()->route('candidat.profil')
            ->with('success', 'Attestation ajoutée.');
    }

    public function destroy(TalentAttestation $attestation)
    {
        abort_unless($attestation->profil->user_id === Auth::id(), 403);
        Storage::disk('public')->delete($attestation->fichier);
        $attestation->delete();

        return redirect()->route('candidat.profil')
            ->with('success', 'Attestation supprimée.');
    }
}
