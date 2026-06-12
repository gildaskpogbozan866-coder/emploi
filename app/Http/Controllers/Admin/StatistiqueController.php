<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Candidature;
use App\Models\Commande;
use App\Models\CV;
use App\Models\Offre;
use App\Models\Paiement;
use App\Models\User;
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

        // Candidatures par mois (6 derniers mois)
        $candidaturesParMois = Candidature::select(
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

        // Top 5 offres par vues
        $topOffresVues = Offre::with('recruteur')
            ->orderByDesc('vues')
            ->limit(5)
            ->get(['id', 'titre', 'entreprise', 'vues', 'recruteur_id', 'statut']);

        // Top 5 recruteurs par candidatures reçues
        $topRecruteurs = User::where('role', 'recruteur')
            ->withCount(['offres as total_candidatures' => function ($q) {
                $q->join('candidatures', 'offres.id', '=', 'candidatures.offre_id');
            }])
            ->orderByDesc('total_candidatures')
            ->limit(5)
            ->get(['id', 'nom', 'prenom', 'email', 'entreprise']);

        // Taux de conversion global : (candidatures / vues totales) × 100
        $totalVues         = Offre::sum('vues') ?: 1;
        $totalCandidatures = Candidature::count();
        $tauxConversion    = round($totalCandidatures / $totalVues * 100, 1);

        // Candidatures par statut
        $candidaturesParStatut = Candidature::select('statut', DB::raw('COUNT(*) as total'))
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
            'candidatures' => $totalCandidatures,
            'cvs'          => CV::count(),
            'commandes'    => Commande::count(),
            'articles'     => Article::publie()->count(),
            'revenus_30j'  => $paiements30j,
        ];

        return view('admin.statistiques', compact(
            'inscriptions',
            'candidaturesParMois',
            'offresParType',
            'offresParStatut',
            'topOffresVues',
            'topRecruteurs',
            'tauxConversion',
            'candidaturesParStatut',
            'totaux'
        ));
    }
}
