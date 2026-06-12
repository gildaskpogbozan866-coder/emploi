<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Offre;
use App\Models\Candidature;
use App\Models\CV;
use App\Models\Commande;
use App\Models\Article;
use App\Models\Paiement;
use App\Models\Document;
use App\Models\Signalement;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'candidats'   => User::where('role', 'candidat')->count(),
            'recruteurs'  => User::where('role', 'recruteur')->count(),
            'offres'      => Offre::count(),
            'offres_actives' => Offre::where('statut', 'active')->count(),
            'cvs'         => CV::count(),
            'documents'   => Document::count(),
            'candidatures'=> Candidature::count(),
            'commandes'   => Commande::count(),
            'paiements'   => Paiement::where('statut', 'confirme')->sum('montant'),
            'signalements'=> Signalement::where('statut', 'en_attente')->count(),
        ];

        $derniers_utilisateurs = User::latest()->limit(5)->get();
        $dernieres_offres      = Offre::with('recruteur')->latest()->limit(5)->get();
        $dernieres_commandes   = Commande::with(['user', 'service'])->latest()->limit(5)->get();
        $derniers_signalements = Signalement::with('user')->where('statut', 'en_attente')->latest()->limit(5)->get();

        return view('admin.dashboard', compact(
            'stats', 'derniers_utilisateurs', 'dernieres_offres',
            'dernieres_commandes', 'derniers_signalements'
        ));
    }
}
