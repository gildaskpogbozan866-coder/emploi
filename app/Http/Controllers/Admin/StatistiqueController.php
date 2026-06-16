<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Candidature;
use App\Models\Commande;
use App\Models\CV;
use App\Models\Offre;
use App\Models\PageVisit;
use App\Models\Paiement;
use App\Models\User;
use Illuminate\Http\Request;
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

    public function terminaux(Request $request)
    {
        $periode = $request->get('periode', '30');

        $base = PageVisit::query();
        if ($periode !== 'tout') {
            $base->where('created_at', '>=', now()->subDays((int) $periode));
        }

        $total   = (clone $base)->count();
        $uniques = (clone $base)->distinct('session_id')->count('session_id');

        // Répartition par device
        $parDevice = (clone $base)
            ->selectRaw('device_type, COUNT(*) as total')
            ->groupBy('device_type')
            ->pluck('total', 'device_type')
            ->toArray();

        $devices = [
            'mobile'     => $parDevice['mobile']     ?? 0,
            'tablette'   => $parDevice['tablette']   ?? 0,
            'ordinateur' => $parDevice['ordinateur'] ?? 0,
        ];

        // Répartition par navigateur
        $parBrowser = (clone $base)
            ->selectRaw('browser, COUNT(*) as total')
            ->groupBy('browser')
            ->orderByDesc('total')
            ->pluck('total', 'browser');

        // Répartition par OS
        $parOs = (clone $base)
            ->selectRaw('os, COUNT(*) as total')
            ->groupBy('os')
            ->orderByDesc('total')
            ->pluck('total', 'os');

        // Top pays (hors Local)
        $topPays = (clone $base)
            ->whereNotNull('country')
            ->where('country', '!=', 'Local')
            ->selectRaw('country, COUNT(*) as total')
            ->groupBy('country')
            ->orderByDesc('total')
            ->limit(25)
            ->get();

        // Top villes (hors Local)
        $topVilles = (clone $base)
            ->whereNotNull('city')
            ->where('city', '!=', 'Local')
            ->selectRaw('city, country, COUNT(*) as total')
            ->groupBy('city', 'country')
            ->orderByDesc('total')
            ->limit(25)
            ->get();

        // Visites par jour (pour mini graphique tendance)
        $parJour = (clone $base)
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as jour, COUNT(*) as total')
            ->groupBy('jour')
            ->orderBy('jour')
            ->pluck('total', 'jour');

        return view('admin.statistiques-terminaux', compact(
            'total', 'uniques', 'devices', 'parBrowser', 'parOs',
            'topPays', 'topVilles', 'parJour', 'periode'
        ));
    }
}
