<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load([
            'candidatures.offre',
            'cvs',
            'abonnements',
        ]);

        $stats = [
            'candidatures'  => $user->candidatures->count(),
            'cvs'           => $user->cvs->count(),
            'offres_vues'   => $user->candidatures->where('statut', 'vue')->count(),
            'retenues'      => $user->candidatures->where('statut', 'retenue')->count(),
        ];

        $dernieres_candidatures = $user->candidatures()
            ->with('offre.recruteur')
            ->latest()
            ->limit(5)
            ->get();

        return view('candidat.dashboard', compact('user', 'stats', 'dernieres_candidatures'));
    }
}
