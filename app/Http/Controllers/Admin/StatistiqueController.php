<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Offre;
use App\Models\Candidature;
use App\Models\CV;
use App\Models\Commande;
use App\Models\Paiement;
use App\Models\Article;
use Illuminate\Support\Facades\DB;

class StatistiqueController extends Controller
{
    public function index()
    {
        // Inscriptions par mois (6 derniers mois)
        $inscriptions = User::select(
                DB::raw('MONTH(created_at) as mois'),
                DB::raw('YEAR(created_at) as annee'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('annee', 'mois')
            ->orderBy('annee')->orderBy('mois')
            ->get();

        // Offres par type
        $offresParType = Offre::select('type', DB::raw('COUNT(*) as total'))
            ->groupBy('type')
            ->get();

        // Offres par statut
        $offresParStatut = Offre::select('statut', DB::raw('COUNT(*) as total'))
            ->groupBy('statut')
            ->get();

        // Paiements des 30 derniers jours
        $paiements30j = Paiement::where('statut', 'confirme')
            ->where('created_at', '>=', now()->subDays(30))
            ->sum('montant');

        // Totaux globaux
        $totaux = [
            'users'        => User::count(),
            'offres'       => Offre::count(),
            'candidatures' => Candidature::count(),
            'cvs'          => CV::count(),
            'commandes'    => Commande::count(),
            'articles'     => Article::publie()->count(),
            'revenus_30j'  => $paiements30j,
        ];

        return view('admin.statistiques', compact('inscriptions', 'offresParType', 'offresParStatut', 'totaux'));
    }
}
