<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Offre;
use App\Models\Candidature;
use App\Models\CV;
use App\Models\Commande;
use App\Models\Paiement;
use App\Models\Document;
use App\Models\Signalement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'candidats'      => User::where('role', 'candidat')->count(),
            'recruteurs'     => User::where('role', 'recruteur')->count(),
            'annonceurs'     => User::where('role', 'annonceur')->count(),
            'offres'         => Offre::count(),
            'offres_actives' => Offre::where('statut', 'active')->count(),
            'cvs'            => CV::count(),
            'documents'      => Document::count(),
            'candidatures'   => Candidature::count(),
            'commandes'      => Commande::count(),
            'paiements'      => Paiement::where('statut', 'confirme')->sum('montant'),
            'signalements'   => Signalement::where('statut', 'en_attente')->count(),
        ];

        // Tables (10 lignes + colonnes supplémentaires pour les filtres)
        $derniers_utilisateurs = User::latest()->limit(10)->get();
        $dernieres_offres      = Offre::with('recruteur')->latest()->limit(10)->get();
        $dernieres_commandes   = Commande::with(['user', 'service'])->latest()->limit(10)->get();
        $derniers_signalements = Signalement::with('user')->where('statut', 'en_attente')->latest()->limit(10)->get();

        // Tendances sur 6 mois
        $moisLabels    = [];
        $inscriptions  = [];
        $candidaturesM = [];
        $revenusM      = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $moisLabels[]    = $date->locale('fr')->isoFormat('MMM YY');
            $inscriptions[]  = User::whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count();
            $candidaturesM[] = Candidature::whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count();
            $revenusM[]      = (int) Paiement::where('statut', 'confirme')
                ->whereYear('paid_at', $date->year)
                ->whereMonth('paid_at', $date->month)
                ->sum('montant');
        }

        // Offres par type de contrat
        $offresParType = DB::table('offres')
            ->select('type', DB::raw('COUNT(*) as total'))
            ->whereNotNull('type')
            ->where('type', '!=', '')
            ->groupBy('type')
            ->orderByDesc('total')
            ->get();

        // Candidatures par statut
        $candParStatut = DB::table('candidatures')
            ->select('statut', DB::raw('COUNT(*) as total'))
            ->groupBy('statut')
            ->get();

        // Top 5 offres par vues
        $topOffresVues = Offre::orderByDesc('vues')->limit(5)->get(['titre', 'entreprise', 'vues']);

        // Top 5 recruteurs par candidatures reçues
        $topRecruteurs = User::where('role', 'recruteur')
            ->withCount(['offres as total_candidatures' => fn($q) => $q->join('candidatures', 'offres.id', '=', 'candidatures.offre_id')])
            ->orderByDesc('total_candidatures')
            ->limit(5)
            ->get(['id', 'nom', 'prenom', 'email', 'entreprise']);

        // Taux de conversion vues → candidatures
        $totalVues      = Offre::sum('vues') ?: 1;
        $tauxConversion = round($stats['candidatures'] / $totalVues * 100, 1);

        $chartData = [
            'moisLabels'   => $moisLabels,
            'inscriptions' => $inscriptions,
            'candidatures' => $candidaturesM,
            'revenus'      => $revenusM,
            'offresStatut' => [
                'labels' => ['Actives', 'En attente', 'Expirées', 'Rejetées'],
                'values' => [
                    Offre::where('statut', 'active')->count(),
                    Offre::where('statut', 'en_attente')->count(),
                    Offre::where('statut', 'expiree')->count(),
                    Offre::where('statut', 'rejetee')->count(),
                ],
            ],
            'utilisateurs' => [
                'labels' => ['Candidats', 'Recruteurs', 'Annonceurs'],
                'values' => [$stats['candidats'], $stats['recruteurs'], $stats['annonceurs']],
            ],
            'offresParType' => [
                'labels' => $offresParType->pluck('type')->map(fn($t) => $t ?: '—')->toArray(),
                'values' => $offresParType->pluck('total')->toArray(),
            ],
            'candParStatut' => [
                'labels' => $candParStatut->map(fn($i) => ucfirst(str_replace('_', ' ', $i->statut)))->toArray(),
                'values' => $candParStatut->pluck('total')->toArray(),
            ],
            'topOffres' => [
                'labels' => $topOffresVues->map(fn($o) => Str::limit($o->titre, 30))->toArray(),
                'values' => $topOffresVues->pluck('vues')->toArray(),
            ],
            'tauxConversion' => $tauxConversion,
        ];

        return view('admin.dashboard', compact(
            'stats', 'derniers_utilisateurs', 'dernieres_offres',
            'dernieres_commandes', 'derniers_signalements',
            'chartData', 'topRecruteurs', 'tauxConversion'
        ));
    }
}
