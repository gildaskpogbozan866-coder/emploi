<?php

namespace App\Http\Controllers\Admin;

use App\Events\PaymentConfirmed;
use App\Http\Controllers\Controller;
use App\Models\Paiement;
use App\Models\User;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    public function index(Request $request)
    {
        $query = Paiement::with(['user', 'abonnement.plan'])->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('user', fn ($sq) => $sq
                ->where('prenom', 'like', "%$q%")
                ->orWhere('nom',   'like', "%$q%")
                ->orWhere('email', 'like', "%$q%")
            );
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('categorie')) {
            if ($request->categorie === 'abonnement') {
                $query->whereNotNull('subscription_id');
            } elseif ($request->categorie === 'cv_credits') {
                $query->where('type', 'cv_credits');
            } elseif ($request->categorie === 'service') {
                $query->whereNull('subscription_id')->where('type', '!=', 'cv_credits');
            }
        }

        $paiements = $query->paginate(20)->withQueryString();

        $stats = [
            'total_confirme'       => Paiement::where('statut', 'confirme')->sum('montant'),
            'total_attente'        => Paiement::where('statut', 'en_attente')->sum('montant'),
            'count_attente'        => Paiement::where('statut', 'en_attente')->count(),
            'count_confirme'       => Paiement::where('statut', 'confirme')->count(),
            'count_credits_attente'=> Paiement::where('type', 'cv_credits')->where('statut', 'en_attente')->count(),
        ];

        return view('admin.paiements.list', compact('paiements', 'stats'));
    }

    public function show(Paiement $paiement)
    {
        $paiement->load(['user', 'abonnement.plan']);
        return view('admin.paiements.detail', compact('paiement'));
    }

    public function updateStatut(Request $request, Paiement $paiement)
    {
        $request->validate(['statut' => 'required|in:en_attente,confirme,echec,rembourse']);

        $old = $paiement->statut;
        $new = $request->statut;

        $paiement->update([
            'statut'  => $new,
            'paid_at' => ($new === 'confirme' && $old !== 'confirme') ? now() : $paiement->paid_at,
        ]);

        // Déclencher l'event centralisé à la première confirmation
        if ($new === 'confirme' && $old !== 'confirme') {
            $paiement->refresh();
            PaymentConfirmed::dispatch($paiement);
        }

        // Annuler l'abonnement lié en cas d'échec ou remboursement
        if (in_array($new, ['echec', 'rembourse']) && $paiement->subscription_id) {
            $paiement->abonnement?->update(['status' => 'cancelled']);
        }

        return back()->with('success', 'Statut du paiement mis à jour.');
    }
}
