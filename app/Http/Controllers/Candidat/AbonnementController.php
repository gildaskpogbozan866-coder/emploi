<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\Abonnement;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbonnementController extends Controller
{
    public function index()
    {
        $user       = Auth::user();
        $abonnement = $user->abonnementActif;
        $historique = $user->abonnements()->latest()->get();

        $plans = [
            'gratuit' => ['label' => 'Plan Gratuit', 'prix' => 0,    'features' => ['Dépôt 1 CV', 'Candidature illimitée', 'Alertes basiques']],
            'premium' => ['label' => 'Plan Premium', 'prix' => 5000, 'features' => ['CV mis en avant', 'Visibilité maximale', 'Support prioritaire', 'Accès CVthèque recruteurs']],
        ];

        return view('candidat.abonnement', compact('user', 'abonnement', 'historique', 'plans'));
    }

    public function souscrire(Request $request)
    {
        $request->validate(['plan' => 'required|in:gratuit,premium', 'methode' => 'nullable|string']);

        Auth::user()->abonnements()->where('statut', 'actif')->update(['statut' => 'annule']);

        $abonnement = Abonnement::create([
            'user_id'   => Auth::id(),
            'plan'      => $request->plan,
            'type'      => 'cv',
            'prix'      => $request->plan === 'premium' ? 5000 : 0,
            'statut'    => 'actif',
            'debut_le'  => now(),
            'expire_le' => $request->plan === 'premium' ? now()->addDays(30) : null,
        ]);

        if ($request->plan === 'premium') {
            Paiement::create([
                'user_id'      => Auth::id(),
                'montant'      => 5000,
                'type'         => 'abonnement_cv',
                'payable_id'   => $abonnement->id,
                'payable_type' => Abonnement::class,
                'methode'      => $request->methode ?? 'mobile_money',
                'statut'       => 'en_attente',
            ]);
        }

        Auth::user()->update(['premium' => $request->plan === 'premium']);

        return redirect()->route('candidat.abonnement')
            ->with('success', $request->plan === 'premium'
                ? 'Abonnement Premium activé ! Paiement en cours de vérification.'
                : 'Vous utilisez le plan gratuit.');
    }

    public function historique()
    {
        $paiements = Auth::user()->paiements()->latest()->paginate(15);
        return view('candidat.historique-paiements', compact('paiements'));
    }
}
