<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    public function index(Request $request)
    {
        $query = Paiement::with('user')->latest();

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $paiements = $query->paginate(20)->withQueryString();
        $totalConfirme = Paiement::where('statut', 'confirme')->sum('montant');

        return view('admin.paiements.list', compact('paiements', 'totalConfirme'));
    }

    public function show(Paiement $paiement)
    {
        $paiement->load('user');
        return view('admin.paiements.detail', compact('paiement'));
    }

    public function updateStatut(Request $request, Paiement $paiement)
    {
        $request->validate(['statut' => 'required|in:en_attente,confirme,echec,rembourse']);
        $paiement->update(['statut' => $request->statut]);

        if ($request->statut === 'confirme') {
            $paiement->user->update(['premium' => true]);
        }

        return back()->with('success', 'Paiement mis à jour.');
    }
}
