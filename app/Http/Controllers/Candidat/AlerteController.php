<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\Alerte;
use App\Models\Metier;
use App\Models\Region;
use App\Models\SecteurActivite;
use App\Models\TypeContrat;
use App\Models\User;
use App\Services\AbonnementSchedulingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AlerteController extends Controller
{
    /**
     * Abonnement + limite d'alertes courants, en déclenchant la bascule
     * anticipée si le quota est déjà épuisé (décision explicite du client) —
     * sinon cette page afficherait encore l'ancien plan tant que le candidat
     * n'a pas lui-même tenté de créer une nouvelle alerte.
     */
    private function abonnementEtLimite(User $user, AbonnementSchedulingService $planning): array
    {
        $abonnement = $user->abonnementActif()->with('plan.features')->first();
        $alertLimit = $abonnement ? (int) $abonnement->plan?->getFeature('alert_limit', 0) : 0;

        // N'aide que si le suivant offre vraiment plus d'alertes que l'actuel —
        // renouveler le même plan (même limite) ne raccourcit plus sa durée
        // déjà payée pour rien.
        if ($abonnement && $alertLimit > 0 && $user->alertes()->count() >= $alertLimit
            && $planning->promouvoirSiEpuise($user, $abonnement, 'alert_limit', function ($planProchain) use ($alertLimit) {
                $limiteProchaine = (int) ($planProchain?->getFeature('alert_limit', 0) ?? 0);
                return $limiteProchaine > $alertLimit;
            })) {
            $user       = $user->fresh();
            $abonnement = $user->abonnementActif()->with('plan.features')->first();
            $alertLimit = $abonnement ? (int) $abonnement->plan?->getFeature('alert_limit', 0) : 0;
        }

        return [$abonnement, $alertLimit];
    }

    public function index(AbonnementSchedulingService $planning)
    {
        $user = Auth::user();
        [$abonnement, $alertLimit] = $this->abonnementEtLimite($user, $planning);
        $alertes = $user->alertes()->latest()->get();

        $metiers      = Metier::orderBy('nom')->get();
        $regions      = Region::actifs()->orderBy('nom')->get();
        $typeContrats = TypeContrat::orderBy('libelle')->get();
        $secteurs     = SecteurActivite::orderBy('libelle')->get();

        return view('candidat.alertes', compact(
            'alertes', 'alertLimit', 'abonnement',
            'metiers', 'regions', 'typeContrats', 'secteurs'
        ));
    }

    public function store(Request $request, AbonnementSchedulingService $planning)
    {
        $user = Auth::user();
        [$abonnement, $alertLimit] = $this->abonnementEtLimite($user, $planning);

        if ($alertLimit === 0) {
            return redirect()->route('candidat.abonnement.plans')
                ->with('info', 'Les alertes emploi sont réservées aux abonnés Premium. Passez au plan Premium pour en bénéficier.');
        }

        $request->validate([
            'nom'          => 'nullable|string|max:100',
            'metier'       => 'required_without_all:localisation,type_contrat,secteur|nullable|string|max:200',
            'localisation' => 'nullable|string|max:100',
            'type_contrat' => 'nullable|string|max:50',
            'secteur'      => 'nullable|string|max:100',
            'frequence'    => 'required|in:immediat,quotidien,hebdomadaire',
        ], [
            'metier.required_without_all' => 'Veuillez renseigner au moins un critère (métier, localisation, type de contrat ou secteur).',
        ]);

        $nom = $request->nom
            ?: implode(' · ', array_filter([
                $request->metier,
                $request->type_contrat,
                $request->localisation,
            ]))
            ?: 'Mon alerte';

        $resultat = DB::transaction(function () use ($user, $alertLimit, $request, $nom) {
            $alertesExistantes = $user->alertes()->lockForUpdate()->get();

            if ($alertesExistantes->count() >= $alertLimit) {
                return 'limite';
            }

            // Même métier/localisation/type de contrat/secteur qu'une alerte déjà
            // existante (peu importe qu'elle soit active ou non, ou sa fréquence
            // de notification) — sinon le candidat reçoit deux fois la même
            // notification pour une seule offre correspondante.
            $critere = fn($a) => [$a->metier ?: null, $a->localisation ?: null, $a->type_contrat ?: null, $a->secteur ?: null];
            $nouveauCritere = [
                $request->metier ?: null,
                $request->localisation ?: null,
                $request->type_contrat ?: null,
                $request->secteur ?: null,
            ];
            if ($alertesExistantes->contains(fn($a) => $critere($a) === $nouveauCritere)) {
                return 'doublon';
            }

            Alerte::create([
                'user_id'      => $user->id,
                'nom'          => $nom,
                'metier'       => $request->metier,
                'localisation' => $request->localisation,
                'type_contrat' => $request->type_contrat,
                'secteur'      => $request->secteur,
                'frequence'    => $request->frequence,
                'active'       => true,
            ]);

            return 'cree';
        });

        if ($resultat === 'limite') {
            return back()->with('error', "Vous avez atteint votre limite de {$alertLimit} alerte(s). Supprimez-en une ou passez au plan Premium.");
        }

        if ($resultat === 'doublon') {
            return back()->with('error', 'Vous avez déjà une alerte avec exactement ces critères.');
        }

        return back()->with('success', 'Alerte créée ! Vous serez notifié(e) selon la fréquence choisie.');
    }

    public function toggle(Alerte $alerte)
    {
        abort_if((int) $alerte->user_id !== (int) Auth::id(), 403);
        $alerte->update(['active' => ! $alerte->active]);
        $label = $alerte->active ? 'activée' : 'désactivée';
        return back()->with('success', "Alerte {$label}.");
    }

    public function destroy(Alerte $alerte)
    {
        abort_if((int) $alerte->user_id !== (int) Auth::id(), 403);
        $alerte->delete();
        return back()->with('success', 'Alerte supprimée.');
    }
}
