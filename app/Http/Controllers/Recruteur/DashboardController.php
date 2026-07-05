<?php

namespace App\Http\Controllers\Recruteur;

use App\Http\Controllers\Controller;
use App\Models\CvDownload;
use App\Services\JobPostQuotaService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(JobPostQuotaService $jobPostQuota)
    {
        $user = Auth::user();

        // Déclenché avant toute autre lecture d'abonnement : quotaFor() peut
        // faire basculer l'abonnement actif si celui-ci est épuisé. Tout ce
        // qui est affiché ensuite (bannière "programmé"...) doit refléter ce
        // nouvel état, sans attendre que le recruteur visite "Mon abonnement"
        // ou tente lui-même de publier une offre.
        $jobPostQuota->quotaFor($user);
        $user = $user->fresh();

        $stats = [
            'offres_actives'    => $user->offres()->where('statut', 'active')->count(),
            'offres_total'      => $user->offres()->count(),
            'candidatures_total'=> \App\Models\Candidature::whereIn('offre_id', $user->offres->pluck('id'))->count(),
            'nouvelles_candid'  => \App\Models\Candidature::whereIn('offre_id', $user->offres->pluck('id'))->where('statut', 'envoyee')->count(),
        ];

        $dernieres_offres = $user->offres()
            ->withCount('candidatures')
            ->latest()
            ->limit(5)
            ->get();

        $dernieres_candid = \App\Models\Candidature::whereIn('offre_id', $user->offres->pluck('id'))
            ->with(['candidat', 'offre'])
            ->latest()
            ->limit(5)
            ->get();

        $cvStats = [
            'credits_restants'   => $user->cv_credits,
            'cvs_telecharges'    => CvDownload::where('recruteur_id', $user->id)->count(),
            'credits_total'      => $user->cv_credits + CvDownload::where('recruteur_id', $user->id)->count(),
        ];

        // Abonnement déjà souscrit mais qui ne prendra effet qu'à l'expiration
        // de l'actuel (cf. AbonnementSchedulingService::dateDebut()) — affiché
        // pour que l'utilisateur comprenne pourquoi il n'en profite pas encore.
        $abonnement = $user->abonnementActif()->with('plan')->first();
        $abonnementProgramme = $user->abonnements()
            ->where('status', 'active')
            ->where('starts_at', '>', now())
            ->with('plan')
            ->orderBy('starts_at')
            ->first();

        return view('recruteur.dashboard', compact('user', 'stats', 'dernieres_offres', 'dernieres_candid', 'cvStats', 'abonnement', 'abonnementProgramme'));
    }
}
