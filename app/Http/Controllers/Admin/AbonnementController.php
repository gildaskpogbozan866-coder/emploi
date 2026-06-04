<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Abonnement;
use Illuminate\Http\Request;

class AbonnementController extends Controller
{
    public function index(Request $request)
    {
        $query = Abonnement::with('user')->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $abonnements = $query->paginate(20)->withQueryString();

        $stats = [
            'total_actif'  => Abonnement::where('statut', 'actif')->count(),
            'premium_actif'=> Abonnement::where('statut', 'actif')->where('plan', '!=', 'gratuit')->count(),
        ];

        return view('admin.abonnements', compact('abonnements', 'stats'));
    }
}
