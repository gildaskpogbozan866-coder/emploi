<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\Candidature;
use App\Models\Offre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidatureController extends Controller
{
    public function index()
    {
        $candidatures = Auth::user()
            ->candidatures()
            ->with('offre.recruteur')
            ->latest()
            ->paginate(15);

        return view('candidat.candidatures', compact('candidatures'));
    }

    public function detail(Candidature $candidature)
    {
        abort_if($candidature->candidat_id !== Auth::id(), 403);
        $candidature->load('offre.recruteur');
        return view('candidat.candidature-detail', compact('candidature'));
    }

    public function offresSauvegardees()
    {
        $offres = Auth::user()
            ->offresSauvegardees()
            ->with('recruteur')
            ->latest()
            ->paginate(15);

        return view('candidat.offres-sauvegardees', compact('offres'));
    }

    public function sauvegarder(Offre $offre)
    {
        $user = Auth::user();

        if ($user->offresSauvegardees()->where('offre_id', $offre->id)->exists()) {
            $user->offresSauvegardees()->detach($offre->id);
            $message = 'Offre retirée de vos sauvegardes.';
        } else {
            $user->offresSauvegardees()->attach($offre->id);
            $message = 'Offre sauvegardée !';
        }

        return back()->with('success', $message);
    }
}
