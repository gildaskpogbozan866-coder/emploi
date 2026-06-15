<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\Alerte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlerteController extends Controller
{
    public function index()
    {
        $user       = Auth::user();
        $abonnement = $user->abonnementActif()->with('plan.features')->first();
        $alertLimit = $abonnement ? (int) $abonnement->plan?->getFeature('alert_limit', 0) : 0;
        $alertes    = $user->alertes()->latest()->get();

        return view('candidat.alertes', compact('alertes', 'alertLimit', 'abonnement'));
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

        $currentCount = $user->alertes()->count();
        if ($currentCount >= $alertLimit) {
            return back()->with('error', "Vous avez atteint votre limite de {$alertLimit} alerte(s). Supprimez-en une ou passez au plan Premium.");
        }

        $request->validate([
            'nom'          => 'nullable|string|max:100',
            'mots_cles'    => 'nullable|string|max:200',
            'localisation' => 'nullable|string|max:100',
            'type_contrat' => 'nullable|string|max:50',
            'secteur'      => 'nullable|string|max:100',
            'frequence'    => 'required|in:immediat,quotidien,hebdomadaire',
        ]);

        Alerte::create([
            'user_id'      => Auth::id(),
            'nom'          => $request->nom ?? ($request->mots_cles ?? 'Mon alerte'),
            'mots_cles'    => $request->mots_cles,
            'localisation' => $request->localisation,
            'type_contrat' => $request->type_contrat,
            'secteur'      => $request->secteur,
            'frequence'    => $request->frequence,
            'active'       => true,
        ]);

        return back()->with('success', 'Alerte créée ! Vous serez notifié(e) selon la fréquence choisie.');
    }

    public function destroy(Alerte $alerte)
    {
        abort_if($alerte->user_id !== Auth::id(), 403);
        $alerte->delete();
        return back()->with('success', 'Alerte supprimée.');
    }
}
