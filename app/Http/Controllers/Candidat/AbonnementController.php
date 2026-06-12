<?php

namespace App\Http\Controllers\Candidat;

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
        $user        = Auth::user();
        $abonnement  = $user->abonnementActif()->with('plan')->first();
        $abonnements = $user->abonnements()->with('plan')->latest('starts_at')->get();

        return view('candidat.abonnement', compact('user', 'abonnement', 'abonnements'));
    }

    public function choisirPlan()
    {
        $user       = Auth::user();
        $abonnement = $user->abonnementActif()->with('plan')->first();

        $plans = Plan::where('is_active', true)
                     ->whereIn('target_type', ['candidat', 'both'])
                     ->with('features')
                     ->orderBy('price')
                     ->get();

        return view('candidat.abonnement-plans', compact('abonnement', 'plans'));
    }

    public function souscrire(Request $request)
    {
        $request->validate(['plan_id' => 'required|integer|exists:plans,id']);

        $plan = Plan::where('is_active', true)
                    ->whereIn('target_type', ['candidat', 'both'])
                    ->findOrFail($request->plan_id);

        Auth::user()->abonnements()
            ->where('status', 'active')
            ->update(['status' => 'cancelled']);

        $abonnement = Abonnement::create([
            'user_id'    => Auth::id(),
            'plan_id'    => $plan->id,
            'status'     => 'cancelled',
            'starts_at'  => now(),
            'ends_at'    => $plan->duration_days ? now()->addDays($plan->duration_days) : null,
            'auto_renew' => false,
        ]);

        Paiement::create([
            'user_id'         => Auth::id(),
            'subscription_id' => $abonnement->id,
            'montant'         => $plan->price,
            'devise'          => $plan->currency,
            'type'            => 'abonnement_candidat',
            'methode'         => 'mobile_money',
            'statut'          => 'en_attente',
        ]);

        return redirect()->route('candidat.abonnement')
            ->with('success', $plan->is_free
                ? 'Plan gratuit activé avec succès.'
                : 'Demande envoyée ! Un conseiller vous contactera pour finaliser le paiement.');
    }

    public function historique()
    {
        $paiements = Auth::user()->paiements()->latest()->paginate(15);
        return view('candidat.historique-paiements', compact('paiements'));
    }
}
