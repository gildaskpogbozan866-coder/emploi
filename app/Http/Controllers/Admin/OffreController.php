<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offre;
use App\Jobs\NotifierAlertesOffreJob;
use Illuminate\Http\Request;

class OffreController extends Controller
{
    public function index(Request $request)
    {
        $query = Offre::with('recruteur')->withCount('candidatures')->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sq) use ($q) {
                $sq->where('titre', 'like', "%$q%")->orWhere('entreprise', 'like', "%$q%");
            });
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $offres = $query->paginate(20)->withQueryString();
        return view('admin.offres.list', compact('offres'));
    }

    public function show(Offre $offre)
    {
        $offre->load(['recruteur', 'candidatures.candidat', 'competences']);
        return view('admin.offres.detail', compact('offre'));
    }

    public function updateStatut(Request $request, Offre $offre)
    {
        $request->validate(['statut' => 'required|in:en_attente,active,expiree,suspendue']);

        $ancienStatut = $offre->statut;
        $data = ['statut' => $request->statut];

        // published_at conditionne les alertes quotidiennes/hebdomadaires
        // (AlerteService::notifierDigest) — renseigné une seule fois, à la
        // première activation, jamais réécrasé sur une réactivation ultérieure.
        if ($ancienStatut !== 'active' && $request->statut === 'active' && !$offre->published_at) {
            $data['published_at'] = now();
        }

        $offre->update($data);

        if ($ancienStatut !== 'active' && $request->statut === 'active') {
            NotifierAlertesOffreJob::dispatch($offre);
        }

        return back()->with('success', 'Statut de l\'offre mis à jour.');
    }

    public function destroy(Offre $offre)
    {
        $offre->delete();
        return redirect()->route('admin.offres.list')->with('success', 'Offre supprimée.');
    }
}
