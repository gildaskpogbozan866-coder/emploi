<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Service;
use App\Services\CvQuotaService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(CvQuotaService $quotaService)
    {
        $user = Auth::user()->load([
            'candidatures.offre',
            'cvs',
            'documents',
            'abonnements',
        ]);

        $totalDocs = $user->cvs->count() + $user->documents->count();

        $stats = [
            'candidatures' => $user->candidatures->count(),
            'cvs'          => $totalDocs,
            'offres_vues'  => $user->candidatures->where('statut', 'vue')->count(),
            'retenues'     => $user->candidatures->where('statut', 'retenue')->count(),
        ];

        $dernieres_candidatures = $user->candidatures()
            ->with('offre.recruteur')
            ->latest()
            ->limit(5)
            ->get();

        $plan_premium = Plan::where('is_active', 1)->where('is_premium', 1)->where("target_type", 'candidat')->first();
        $cvService    = Service::where('slug', 'cv-professionnel')->where('actif', true)->first();

        // Déclenché avant toute autre lecture d'abonnement : quotaFor() peut
        // faire basculer l'abonnement actif si celui-ci est épuisé. Tout ce
        // qui est affiché ensuite (bannière, "programmé"...) doit refléter ce
        // nouvel état, jamais un mélange ancien/nouveau plan.
        $cvQuota = $quotaService->quotaFor($user);
        $user    = $user->fresh()->load(['candidatures', 'abonnements']);

        $abonnement = $user->abonnementActif()->with('plan.features')->first();
        $quotas     = null;

        // Abonnement déjà souscrit mais qui ne prendra effet qu'à l'expiration
        // de l'actuel (cf. AbonnementSchedulingService::dateDebut()) — affiché
        // pour que l'utilisateur comprenne pourquoi il n'en profite pas encore.
        $abonnementProgramme = $user->abonnements
            ->where('status', 'active')
            ->filter(fn ($a) => $a->starts_at->isFuture())
            ->sortBy('starts_at')
            ->first();

        if ($abonnement) {
            $features = $abonnement->plan?->features?->keyBy('feature_key') ?? collect();
            $appLimit  = (int) ($features->get('job_apply_limit')?->feature_value ?? 0);
            $since     = $abonnement->starts_at ?? $user->created_at;

            $quotas = [
                'plan'     => $abonnement->plan,
                'ends_at'  => $abonnement->ends_at,
                'cvs' => [
                    'used'      => $cvQuota['used'],
                    'limit'     => $cvQuota['limit'],
                    'unlimited' => $cvQuota['unlimited'],
                ],
                'candidatures' => [
                    'used'      => $user->candidatures->where('created_at', '>=', $since)->count(),
                    'limit'     => $appLimit,
                    'unlimited' => $appLimit === 0,
                ],
                'featured_profile' => (int) ($features->get('featured_profile')?->feature_value ?? 0) > 0,
            ];
        }

        return view('candidat.dashboard', compact('user', 'stats', 'dernieres_candidatures', 'abonnement', 'abonnementProgramme', 'quotas', 'plan_premium', 'cvService'));
    }
}
