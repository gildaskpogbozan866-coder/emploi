<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\Alerte;
use App\Models\Metier;
use App\Models\Region;
use App\Models\SecteurActivite;
use App\Models\TypeContrat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AlerteController extends Controller
{
    public function index()
    {
        $user       = Auth::user();
        $abonnement = $user->abonnementActif()->with('plan.features')->first();
        $alertLimit = $abonnement ? (int) $abonnement->plan?->getFeature('alert_limit', 0) : 0;
        $alertes    = $user->alertes()->latest()->get();

        $metiers      = Metier::orderBy('nom')->get();
        $regions      = Region::actifs()->orderBy('nom')->get();
        $typeContrats = TypeContrat::orderBy('libelle')->get();
        $secteurs     = SecteurActivite::orderBy('libelle')->get();

        return view('candidat.alertes', compact(
            'alertes', 'alertLimit', 'abonnement',
            'metiers', 'regions', 'typeContrats', 'secteurs'
        ));
    }

    public function store(Request $request)
    {
        $user       = Auth::user();
        $abonnement = $user->abonnementActif()->with('plan.features')->first();
        $alertLimit = $abonnement ? (int) $abonnement->plan?->getFeature('alert_limit', 0) : 0;

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

        $created = DB::transaction(function () use ($user, $alertLimit, $request, $nom) {
            $currentCount = $user->alertes()->lockForUpdate()->count();
            if ($currentCount >= $alertLimit) {
                return false;
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

            return true;
        });

        if (! $created) {
            return back()->with('error', "Vous avez atteint votre limite de {$alertLimit} alerte(s). Supprimez-en une ou passez au plan Premium.");
        }

        return back()->with('success', 'Alerte créée ! Vous serez notifié(e) selon la fréquence choisie.');
    }

    public function toggle(Alerte $alerte)
    {
        abort_if($alerte->user_id !== Auth::id(), 403);
        $alerte->update(['active' => ! $alerte->active]);
        $label = $alerte->active ? 'activée' : 'désactivée';
        return back()->with('success', "Alerte {$label}.");
    }

    public function destroy(Alerte $alerte)
    {
        abort_if($alerte->user_id !== Auth::id(), 403);
        $alerte->delete();
        return back()->with('success', 'Alerte supprimée.');
    }
}
