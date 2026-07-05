<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\Abonnement;
use App\Models\Paiement;
use App\Models\Plan;
use App\Services\AbonnementSchedulingService;
use App\Services\CvQuotaService;
use App\Services\QuotaCycleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbonnementController extends Controller
{
    public function index(CvQuotaService $quotaService, QuotaCycleService $cycles)
    {
        $user = Auth::user();

        // Déclenché en premier, avant toute autre lecture : quotaFor() peut
        // faire basculer l'abonnement actif si celui-ci est épuisé. Tout ce
        // qui est lu après (plan actuel, historique, "programmé"...) doit
        // refléter ce nouvel état, jamais un mélange ancien/nouveau plan.
        $cvQuota = $quotaService->quotaFor($user);
        $user    = $user->fresh();

        $abonnement  = $user->abonnementActif()->with('plan.features')->first();
        $abonnements = $user->abonnements()->with('plan')->latest('starts_at')->get();

        // Abonnement déjà souscrit mais qui ne prendra effet qu'à l'expiration
        // de l'actuel — affiché clairement ici aussi (pas seulement sur le
        // tableau de bord et la sidebar), puisque c'est la page où l'utilisateur
        // vient justement chercher cette information.
        $abonnementProgramme = $abonnements
            ->where('status', 'active')
            ->filter(fn ($a) => $a->starts_at->isFuture())
            ->sortBy('starts_at')
            ->first();

        $quotas = $this->buildQuotas($user, $abonnement, $cvQuota, $cycles);

        return view('candidat.abonnement', compact('user', 'abonnement', 'abonnements', 'quotas', 'abonnementProgramme'));
    }

    private function buildQuotas($user, $abonnement, array $cvQuota, QuotaCycleService $cycles): array
    {
        if (!$abonnement) return [];

        $plan  = $abonnement->plan;
        $since = $cycles->cycleFor($user, 'job_apply_limit', $abonnement)->cycle_starts_at;

        // Le quota de CV ne compte que les vrais CV, jamais les documents (diplôme,
        // attestation...) — sinon un candidat qui n'a déposé que des diplômes se
        // voyait afficher "quota CV atteint" sans avoir jamais créé de CV.
        $docsCount = $user->documents()->count();

        $candidatures = $user->candidatures()->where('created_at', '>=', $since)->count();
        $applyLimit   = (int) $plan->getFeature('job_apply_limit', 10);

        $featuredProfile = (bool) (int) $plan->getFeature('featured_profile', 0);

        return [
            'cvs' => [
                'label'     => 'CV créés',
                'used'      => $cvQuota['used'],
                'limit'     => $cvQuota['limit'],
                'unlimited' => $cvQuota['unlimited'],
            ],
            'documents' => [
                'label' => 'Autres documents (diplômes, attestations...)',
                'used'  => $docsCount,
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

    public function choisirPlan(CvQuotaService $cvQuotaService)
    {
        $user       = Auth::user();
        $abonnement = $user->abonnementActif()->with('plan.features')->first();

        $plans = Plan::where('is_active', true)
                     ->whereIn('target_type', ['candidat', 'both'])
                     ->with('features')
                     ->orderBy('price')
                     ->get();

        $hasUsedFreePlan = $user->abonnements()
            ->whereHas('plan', fn($q) => $q->where('is_free', true))
            ->exists();

        // Quotas STOCK (CV, alertes) déjà au plafond sur l'abonnement actif —
        // pour avertir avant paiement qu'un renouvellement du même palier ne
        // débloquera rien de plus (avertissement non bloquant, l'utilisateur
        // reste libre de payer quand même).
        $stockQuotasSatures = [];
        if ($abonnement) {
            $cv = $cvQuotaService->quotaFor($user);
            if (!$cv['unlimited'] && $cv['reached']) {
                $stockQuotasSatures[] = ['label' => 'CV stockables', 'limit' => $cv['limit']];
            }

            $alertLimit = (int) ($abonnement->plan?->getFeature('alert_limit', 0) ?? 0);
            if ($alertLimit > 0 && $user->alertes()->count() >= $alertLimit) {
                $stockQuotasSatures[] = ['label' => 'alertes emploi', 'limit' => $alertLimit];
            }
        }

        return view('candidat.abonnement-plans', compact('abonnement', 'plans', 'hasUsedFreePlan', 'stockQuotasSatures'));
    }

    public function pourquoiPremium()
    {
        $abonnement = auth()->user()->abonnementActif()->with('plan')->first();
        if ($abonnement && !($abonnement->plan?->is_free ?? true)) {
            return redirect()->route('candidat.abonnement')
                ->with('info', 'Vous êtes déjà abonné au plan ' . $abonnement->plan->name . '.');
        }

        $plan_premium = Plan::where('is_active', true)
                            ->where('is_premium', true)
                            ->where('target_type', 'candidat')
                            ->first();

        return view('candidat.pourquoi-premium', compact('plan_premium'));
    }

    public function souscrire(Request $request, AbonnementSchedulingService $planning)
    {
        $request->validate(['plan_id' => 'required|integer|exists:plans,id']);

        $plan = Plan::where('is_active', true)
                    ->whereIn('target_type', ['candidat', 'both'])
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
            return redirect()->route('candidat.abonnement')
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
            'type'            => 'abonnement_candidat',
            'methode'         => 'en_attente',
            'statut'          => 'en_attente',
            'gateway'         => 'manuel',
        ]);

        return redirect()->route('payment.choose', ['paiement' => $paiement->id]);
    }
}
