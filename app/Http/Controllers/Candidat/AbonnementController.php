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
        $abonnement  = $user->abonnementActif()->with('plan.features')->first();
        $abonnements = $user->abonnements()->with('plan')->latest('starts_at')->get();

        $quotas = $this->buildQuotas($user, $abonnement);

        return view('candidat.abonnement', compact('user', 'abonnement', 'abonnements', 'quotas'));
    }

    private function buildQuotas($user, $abonnement): array
    {
        if (!$abonnement) return [];

        $plan  = $abonnement->plan;
        $since = $abonnement->starts_at;

        $cvsCount    = $user->cvs()->count() + $user->documents()->count();
        $cvLimit     = (int) $plan->getFeature('cv_limit', 1);

        $candidatures = $user->candidatures()->where('created_at', '>=', $since)->count();
        $applyLimit   = (int) $plan->getFeature('job_apply_limit', 10);

        $featuredProfile = (bool) (int) $plan->getFeature('featured_profile', 0);

        return [
            'cvs' => [
                'label' => 'CVs créés',
                'used'  => $cvsCount,
                'limit' => $cvLimit,
            ],
            'candidatures' => [
                'label'     => 'Candidatures ce cycle',
                'used'      => $candidatures,
                'limit'     => $applyLimit,
                'unlimited' => $applyLimit >= 100,
            ],
            'featured_profile' => [
                'label'   => 'Profil mis en avant',
                'enabled' => $featuredProfile,
            ],
        ];
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

        if ($plan->is_free) {
            Abonnement::create([
                'user_id'    => Auth::id(),
                'plan_id'    => $plan->id,
                'status'     => 'active',
                'starts_at'  => now(),
                'ends_at'    => $plan->duration_days ? now()->addDays($plan->duration_days) : null,
                'auto_renew' => false,
            ]);
            return redirect()->route('candidat.abonnement')
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
            'type'            => 'abonnement_candidat',
            'methode'         => 'en_attente',
            'statut'          => 'en_attente',
            'gateway'         => 'manuel',
        ]);

        return redirect()->route('payment.choose', ['paiement' => $paiement->id]);
    }
}
