<?php

namespace App\Http\Controllers\Recruteur;

use App\Http\Controllers\Controller;
use App\Models\Abonnement;
use App\Models\Paiement;
use App\Models\Plan;
use App\Services\AbonnementSchedulingService;
use App\Services\JobPostQuotaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbonnementController extends Controller
{
    public function index(JobPostQuotaService $jobPostQuota)
    {
        $user = Auth::user();

        // Déclenche la même bascule anticipée que verifierQuota() (dès qu'un
        // avantage est épuisé et qu'un plan est déjà programmé) — sinon cette
        // page afficherait encore l'ancien plan tant que le recruteur n'a pas
        // lui-même tenté de publier une offre.
        $offresQuota = $jobPostQuota->quotaFor($user);
        $user        = $user->fresh();

        $abonnement = $user->abonnementActif()->with('plan.features')->first();

        $abonnements = $user->abonnements()
                            ->with('plan')
                            ->latest('starts_at')
                            ->get();

        // Abonnement déjà souscrit mais qui ne prendra effet qu'à l'expiration
        // de l'actuel — affiché clairement ici aussi (pas seulement sur le
        // tableau de bord et la sidebar), puisque c'est la page où l'utilisateur
        // vient justement chercher cette information.
        $abonnementProgramme = $abonnements
            ->where('status', 'active')
            ->filter(fn ($a) => $a->starts_at->isFuture())
            ->sortBy('starts_at')
            ->first();

        $quotas = $this->buildQuotas($abonnement, $offresQuota);

        return view('recruteur.abonnement', compact('user', 'abonnement', 'abonnements', 'quotas', 'abonnementProgramme'));
    }

    private function buildQuotas($abonnement, array $offresQuota): array
    {
        if (!$abonnement) return [];

        $plan = $abonnement->plan;

        $featuredLimit    = (int) $plan->getFeature('featured_jobs', 0);
        $candidateSearch  = (bool) (int) $plan->getFeature('candidate_search', 0);

        return [
            'offres' => [
                'label'     => 'Offres publiées',
                'used'      => $offresQuota['used'],
                'limit'     => $offresQuota['limit'],
                'unlimited' => $offresQuota['unlimited'],
                'disabled'  => $offresQuota['disabled'],
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

        $hasUsedFreePlan = $user->abonnements()
            ->whereHas('plan', fn($q) => $q->where('is_free', true))
            ->exists();

        // Aucun quota STOCK n'existe côté recruteur dans la classification
        // produit actuelle (seul job_post_limit, de type FLUX) — tableau
        // toujours vide pour l'instant, gardé pour la cohérence avec la vue
        // candidat et si un futur quota STOCK recruteur est introduit.
        $stockQuotasSatures = [];

        return view('recruteur.abonnement-plans', compact('abonnement', 'plans', 'hasUsedFreePlan', 'stockQuotasSatures'));
    }

    public function souscrire(Request $request, AbonnementSchedulingService $planning)
    {
        $request->validate(['plan_id' => 'required|integer|exists:plans,id']);

        $plan = Plan::where('is_active', true)
                    ->whereIn('target_type', ['recruteur', 'both'])
                    ->findOrFail($request->plan_id);

        if ($plan->is_free) {
            $alreadyUsed = Auth::user()->abonnements()
                ->whereHas('plan', fn($q) => $q->where('is_free', true))
                ->exists();

            if ($alreadyUsed) {
                return back()->with('error', 'Vous avez déjà utilisé le plan gratuit. Passez au Premium pour continuer.');
            }

            // Un abonnement en cours (ou déjà programmé) et encore valide n'est
            // jamais annulé — le nouveau prend le relais à son expiration.
            $startsAt = $planning->dateDebut(Auth::user());

            Abonnement::create([
                'user_id'    => Auth::id(),
                'plan_id'    => $plan->id,
                'status'     => 'active',
                'starts_at'  => $startsAt,
                'ends_at'    => $plan->duration_days ? $startsAt->copy()->addDays($plan->duration_days) : null,
                'auto_renew' => false,
            ]);
            return redirect()->route('recruteur.abonnement')
                ->with('success', $startsAt->isFuture()
                    ? 'Plan gratuit programmé — il prendra le relais le '.$startsAt->format('d/m/Y').', à l\'expiration de votre abonnement actuel.'
                    : 'Plan gratuit activé avec succès.');
        }

        // Le paiement n'étant pas encore confirmé, les dates réelles seront
        // recalculées à la confirmation (HandlePaymentConfirmed::activateAbonnement) —
        // celles-ci ne sont que des valeurs provisoires en attendant.
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
