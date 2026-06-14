<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
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

        // Abonnement actif + quotas
        $abonnement = $user->abonnementActif()->with('plan.features')->first();
        $quotas     = null;

        if ($abonnement) {
            $features = $abonnement->plan?->features?->keyBy('feature_key') ?? collect();
            $cvLimit   = (int) ($features->get('cv_limit')?->feature_value   ?? 0);
            $appLimit  = (int) ($features->get('job_apply_limit')?->feature_value ?? 0);
            $since     = $abonnement->starts_at ?? $user->created_at;

            $quotas = [
                'plan'     => $abonnement->plan,
                'ends_at'  => $abonnement->ends_at,
                'cvs' => [
                    'used'      => $totalDocs,
                    'limit'     => $cvLimit,
                    'unlimited' => $cvLimit === 0,
                ],
                'candidatures' => [
                    'used'      => $user->candidatures->where('created_at', '>=', $since)->count(),
                    'limit'     => $appLimit,
                    'unlimited' => $appLimit === 0,
                ],
                'featured_profile' => (int) ($features->get('featured_profile')?->feature_value ?? 0) > 0,
            ];
        }

        return view('candidat.dashboard', compact('user', 'stats', 'dernieres_candidatures', 'abonnement', 'quotas'));
    }
}
