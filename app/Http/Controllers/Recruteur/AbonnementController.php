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
        $abonnement = $user->abonnementActif()->with('plan.features')->first();

        $abonnements = $user->abonnements()
                            ->with('plan')
                            ->latest('starts_at')
                            ->get();

        $quotas = $this->buildQuotas($user, $abonnement);

        return view('recruteur.abonnement', compact('user', 'abonnement', 'abonnements', 'quotas'));
    }

    private function buildQuotas($user, $abonnement): array
    {
        if (!$abonnement) return [];

        $plan  = $abonnement->plan;
        $since = $abonnement->starts_at;

        $offresPubliees = $user->offres()->where('created_at', '>=', $since)->count();
        $jobLimit       = (int) $plan->getFeature('job_post_limit', 0);

        $featuredLimit    = (int) $plan->getFeature('featured_jobs', 0);
        $candidateSearch  = (bool) (int) $plan->getFeature('candidate_search', 0);

        return [
            'offres' => [
                'label'     => 'Offres publiées',
                'used'      => $offresPubliees,
                'limit'     => $jobLimit,
                'unlimited' => $jobLimit === 0,
            ],
            'featured_jobs' => [
                'label'   => 'Offres mises en avant',
                'limit'   => $featuredLimit,
                'enabled' => $featuredLimit > 0,
            ],
            'candidate_search' => [
                'label'   => 'Accès CVthèque',
                'enabled' => $candidateSearch,
            ],
        ];
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

        if ($plan->is_free) {
            Abonnement::create([
                'user_id'    => Auth::id(),
                'plan_id'    => $plan->id,
                'status'     => 'active',
                'starts_at'  => now(),
                'ends_at'    => $plan->duration_days ? now()->addDays($plan->duration_days) : null,
                'auto_renew' => false,
            ]);
            return redirect()->route('recruteur.abonnement')
                ->with('success', 'Plan gratuit activé avec succès.');
        }

        $abonnement = Abonnement::create([
            'user_id'    => Auth::id(),
            'plan_id'    => $plan->id,
            'status'     => 'cancelled',
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
