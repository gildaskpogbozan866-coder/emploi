<?php

namespace App\Http\Controllers\Recruteur;

use App\Http\Controllers\Controller;
use App\Models\Candidature;
use Illuminate\Support\Facades\Auth;

class StatistiqueController extends Controller
{
    public function index()
    {
        $user     = Auth::user();
        $offresIds = $user->offres()->pluck('id');

        $stats = [
            'offres_total'       => $user->offres()->count(),
            'offres_actives'     => $user->offres()->where('statut', 'active')->count(),
            'offres_expirees'    => $user->offres()->where('statut', 'expiree')->count(),
            'candidatures_total' => Candidature::whereIn('offre_id', $offresIds)->count(),
            'nouvelles_candid'   => Candidature::whereIn('offre_id', $offresIds)->where('statut', 'envoyee')->count(),
            'retenues'           => Candidature::whereIn('offre_id', $offresIds)->where('statut', 'retenue')->count(),
            'entretiens'         => Candidature::whereIn('offre_id', $offresIds)->where('statut', 'entretien')->count(),
        ];

        $dernieres_offres = $user->offres()
            ->withCount('candidatures')
            ->latest()
            ->get();

        return view('recruteur.statistiques', compact('stats', 'dernieres_offres'));
    }
}
