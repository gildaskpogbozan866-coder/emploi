<?php

namespace App\Http\Controllers\Recruteur;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'offres_actives'    => $user->offres()->where('statut', 'active')->count(),
            'offres_total'      => $user->offres()->count(),
            'candidatures_total'=> \App\Models\Candidature::whereIn('offre_id', $user->offres->pluck('id'))->count(),
            'nouvelles_candid'  => \App\Models\Candidature::whereIn('offre_id', $user->offres->pluck('id'))->where('statut', 'envoyee')->count(),
        ];

        $dernieres_offres = $user->offres()
            ->withCount('candidatures')
            ->latest()
            ->limit(5)
            ->get();

        $dernieres_candid = \App\Models\Candidature::whereIn('offre_id', $user->offres->pluck('id'))
            ->with(['candidat', 'offre'])
            ->latest()
            ->limit(5)
            ->get();

        return view('recruteur.dashboard', compact('user', 'stats', 'dernieres_offres', 'dernieres_candid'));
    }
}
