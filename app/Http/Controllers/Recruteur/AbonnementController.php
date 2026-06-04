<?php

namespace App\Http\Controllers\Recruteur;

use App\Http\Controllers\Controller;
use App\Models\Abonnement;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbonnementController extends Controller
{
    public function index()
    {
        $user       = Auth::user();
        $abonnement = $user->abonnementActif;

        $plans = [
            'premium_30' => [
                'label'       => 'Premium',
                'sous_label'  => 'Jusqu\'à 10 annonces / mois',
                'description' => 'Recrutez activement avec 10 annonces Premium, le Matching automatique et tous les outils de gestion professionnels.',
                'prix'        => 30300,
                'badge'       => null,
                'features'    => [
                    'Publication de 10 annonces',
                    'Alerte par email des candidatures reçues',
                    'Accompagnement pendant la diffusion',
                    'Multidiffusion de votre annonce sur nos différentes pages',
                    'Envoi aux candidats en veille de la CVthèque',
                    'Remontée de l\'annonce en tête de liste',
                    'Filtres de gestion des candidatures',
                    'Tri automatique par pertinence (Matching)',
                    'Tableau de synthèse Excel des candidatures',
                ],
                'options'     => [
                    'Réception par email des candidatures',
                    'Blocage des candidatures non pertinentes',
                    'Publication en mode anonyme',
                ],
            ],
            'premium_50' => [
                'label'       => 'Illimité',
                'sous_label'  => 'Annonces illimitées',
                'description' => 'Publiez toutes vos annonces Premium en illimité.',
                'prix'        => 50500,
                'badge'       => 'Meilleur rapport qualité/prix',
                'features'    => [
                    'Publication de vos annonces en illimité',
                    'Alerte par email des candidatures reçues',
                    'Accompagnement pendant la diffusion',
                    'Multidiffusion de votre annonce sur nos différentes pages',
                    'Envoi aux candidats en veille de la CVthèque',
                    'Remontée de l\'annonce en tête de liste',
                    'Filtres de gestion des candidatures',
                    'Tri automatique par pertinence (Matching)',
                    'Tableau de synthèse Excel des candidatures',
                ],
                'options'     => [
                    'Réception par email des candidatures',
                    'Blocage des candidatures non pertinentes',
                    'Publication en mode anonyme',
                    'Publication de toutes vos annonces en illimité',
                ],
            ],
        ];

        return view('recruteur.abonnement', compact('user', 'abonnement', 'plans'));
    }

    public function souscrire(Request $request)
    {
        $request->validate(['plan' => 'required|in:premium_30,premium_50']);

        Auth::user()->abonnements()->where('statut', 'actif')->update(['statut' => 'annule']);

        $prix = match($request->plan) {
            'premium_30' => 30300,
            'premium_50' => 50500,
        };

        $abonnement = Abonnement::create([
            'user_id'   => Auth::id(),
            'plan'      => $request->plan,
            'type'      => 'recruteur',
            'prix'      => $prix,
            'statut'    => 'actif',
            'debut_le'  => now(),
            'expire_le' => now()->addDays(30),
        ]);

        Paiement::create([
            'user_id'      => Auth::id(),
            'montant'      => $prix,
            'type'         => 'abonnement_recruteur',
            'payable_id'   => $abonnement->id,
            'payable_type' => Abonnement::class,
            'methode'      => 'mobile_money',
            'statut'       => 'en_attente',
        ]);

        return redirect()->route('recruteur.abonnement')
            ->with('success', 'Abonnement souscrit ! Un conseiller vous contacte pour finaliser le paiement.');
    }
}
