<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidature;
use Illuminate\Http\Request;

class CandidatureController extends Controller
{
    public function index(Request $request)
    {
        $query = Candidature::with(['offre.recruteur', 'candidat'])->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sq) use ($q) {
                $sq->whereHas('candidat', fn ($cq) => $cq->where('prenom', 'like', "%$q%")->orWhere('nom', 'like', "%$q%")->orWhere('email', 'like', "%$q%"))
                   ->orWhereHas('offre', fn ($oq) => $oq->where('titre', 'like', "%$q%")->orWhere('entreprise', 'like', "%$q%"));
            });
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $candidatures = $query->paginate(20)->withQueryString();

        return view('admin.candidatures.list', compact('candidatures'));
    }

    public function show(Candidature $candidature)
    {
        $candidature->load(['offre.recruteur', 'candidat', 'cv', 'documents.type']);
        return view('admin.candidatures.detail', compact('candidature'));
    }

    public function destroy(Candidature $candidature)
    {
        $candidature->delete();
        return redirect()->route('admin.candidatures.list')->with('success', 'Candidature supprimée.');
    }
}
