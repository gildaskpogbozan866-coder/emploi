<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
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
            } elseif ($request->categorie === 'service') {
                $query->whereNull('subscription_id');
            }
        }

        $paiements = $query->paginate(20)->withQueryString();

        $stats = [
            'total_confirme' => Paiement::where('statut', 'confirme')->sum('montant'),
            'total_attente'  => Paiement::where('statut', 'en_attente')->sum('montant'),
            'count_attente'  => Paiement::where('statut', 'en_attente')->count(),
            'count_confirme' => Paiement::where('statut', 'confirme')->count(),
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

        // Synchroniser le statut de l'abonnement lié
        if ($paiement->subscription_id) {
            $abonnement = $paiement->abonnement()->with('plan')->first();
            if ($abonnement) {
                if ($new === 'confirme' && $old !== 'confirme') {
                    $startsAt = now();
                    $endsAt   = $abonnement->plan?->duration_days
                        ? $startsAt->copy()->addDays($abonnement->plan->duration_days)
                        : null;
                    $abonnement->update([
                        'status'    => 'active',
                        'starts_at' => $startsAt,
                        'ends_at'   => $endsAt,
                    ]);
                } elseif (in_array($new, ['echec', 'rembourse'])) {
                    $abonnement->update(['status' => 'cancelled']);
                }
            }
        }

        return back()->with('success', 'Statut du paiement mis à jour.');
    }
}
