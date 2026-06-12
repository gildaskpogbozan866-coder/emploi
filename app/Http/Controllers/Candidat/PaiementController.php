<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaiementController extends Controller
{
    public function index(Request $request)
    {
        $query = Paiement::where('user_id', Auth::id())->latest();

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $paiements = $query->paginate(15)->withQueryString();

        $stats = [
            'total_paye'  => Paiement::where('user_id', Auth::id())->where('statut', 'confirme')->sum('montant'),
            'nb_confirme' => Paiement::where('user_id', Auth::id())->where('statut', 'confirme')->count(),
            'nb_attente'  => Paiement::where('user_id', Auth::id())->where('statut', 'en_attente')->count(),
        ];

        return view('candidat.paiements', compact('paiements', 'stats'));
    }
}
