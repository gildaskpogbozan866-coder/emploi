<?php

namespace App\Http\Controllers\Recruteur;

use App\Http\Controllers\Controller;
use App\Models\Abonnement;
use App\Models\Paiement;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbonnementController extends Controller
{
    public function index()
    {
        $user       = Auth::user();
        $abonnement = $user->abonnementActif()->with('plan')->first();

        $abonnements = $user->abonnements()
                            ->with('plan')
                            ->latest('starts_at')
                            ->get();

        return view('recruteur.abonnement', compact('user', 'abonnement', 'abonnements'));
    }

    public function choisirPlan()
    {
        $user       = Auth::user();
        $abonnement = $user->abonnementActif()->with('plan')->first();

        $plans = Plan::where('is_active', true)
                     ->whereIn('target_type', ['recruteur', 'both'])
                     ->with('features')
                     ->orderBy('price')
                     ->get();

        return view('recruteur.abonnement-plans', compact('abonnement', 'plans'));
    }

    public function souscrire(Request $request)
    {
        $request->validate(['plan_id' => 'required|integer|exists:plans,id']);

        $plan = Plan::where('is_active', true)
                    ->whereIn('target_type', ['recruteur', 'both'])
                    ->findOrFail($request->plan_id);

        // Annuler l'abonnement actif existant
        Auth::user()->abonnements()
            ->where('status', 'active')
            ->update(['status' => 'cancelled']);

        $abonnement = Abonnement::create([
            'user_id'    => Auth::id(),
            'plan_id'    => $plan->id,
            'status'     => 'cancelled', // activé par l'admin après confirmation du paiement
            'starts_at'  => now(),
            'ends_at'    => $plan->duration_days ? now()->addDays($plan->duration_days) : null,
            'auto_renew' => false,
        ]);

        $paiement = Paiement::create([
            'user_id'         => Auth::id(),
            'subscription_id' => $abonnement->id,
            'montant'         => $plan->price,
            'devise'          => $plan->currency ?? 'XOF',
            'type'            => 'abonnement_recruteur',
            'methode'         => 'en_attente',
            'statut'          => 'en_attente',
            'gateway'         => 'manuel',
        ]);

        return redirect()->route('payment.choose', ['paiement' => $paiement->id]);
    }
}
