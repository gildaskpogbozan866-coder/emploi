<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\Candidature;
use App\Models\Offre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidatureController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->candidatures()->with('offre.recruteur')->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('offre', fn($sq) => $sq->where('titre', 'like', "%$q%")->orWhere('entreprise', 'like', "%$q%"));
        }
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $candidatures = $query->paginate(15)->withQueryString();

        return view('candidat.candidatures', compact('candidatures'));
    }

    public function detail(Candidature $candidature)
    {
        abort_if($candidature->candidat_id !== Auth::id(), 403);
        $candidature->load(['offre.recruteur', 'cv']);
        return view('candidat.candidature-detail', compact('candidature'));
    }

    public function offresSauvegardees(Request $request)
    {
        $query = Auth::user()->offresSauvegardees()->with('recruteur')->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn($sq) => $sq->where('titre', 'like', "%$q%")->orWhere('entreprise', 'like', "%$q%"));
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $offres = $query->paginate(15)->withQueryString();

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
