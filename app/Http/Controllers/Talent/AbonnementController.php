<?php

namespace App\Http\Controllers\Talent;

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
            'gratuit' => ['label' => 'Gratuit',  'prix' => 0,    'features' => ['Profil visible', '10 vues/mois']],
            'premium' => ['label' => 'Premium',  'prix' => 3000, 'features' => ['Profil en tête des résultats', 'Coordonnées visibles par recruteurs', 'Badge Premium ★', 'Vues illimitées']],
        ];

        return view('talent.abonnement', compact('user', 'abonnement', 'plans'));
    }

    public function souscrire(Request $request)
    {
        $request->validate(['plan' => 'required|in:gratuit,premium']);

        Auth::user()->abonnements()->where('statut', 'actif')->update(['statut' => 'annule']);

        $abonnement = Abonnement::create([
            'user_id'   => Auth::id(),
            'plan'      => $request->plan,
            'type'      => 'talent',
            'prix'      => $request->plan === 'premium' ? 3000 : 0,
            'statut'    => 'actif',
            'debut_le'  => now(),
            'expire_le' => $request->plan === 'premium' ? now()->addDays(30) : null,
        ]);

        if ($request->plan === 'premium') {
            Paiement::create([
                'user_id'      => Auth::id(),
                'montant'      => 3000,
                'type'         => 'abonnement_talent',
                'payable_id'   => $abonnement->id,
                'payable_type' => Abonnement::class,
                'methode'      => 'mobile_money',
                'statut'       => 'en_attente',
            ]);
        }

        Auth::user()->update(['premium' => $request->plan === 'premium']);

        return redirect()->route('talent.abonnement')
            ->with('success', $request->plan === 'premium' ? 'Plan Premium activé !' : 'Plan gratuit sélectionné.');
    }
}
