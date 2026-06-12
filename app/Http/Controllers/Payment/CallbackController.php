<?php

namespace App\Http\Controllers\Payment;

use App\Events\PaymentConfirmed;
use App\Http\Controllers\Controller;
use App\Models\Paiement;
use App\Services\FedaPayService;
use App\Services\KKiaPayService;
use Illuminate\Http\Request;

class CallbackController extends Controller
{
    /**
     * Retour navigateur après paiement FedaPay.
     * FedaPay ajoute ?status=approved|declined&id=xxx dans l'URL.
     */
    public function fedapay(Request $request, FedaPayService $fedaPay): \Illuminate\Http\RedirectResponse
    {
        $paiement = Paiement::find($request->route('paiement'));

        if (!$paiement) {
            return redirect()->route('recruteur.dashboard')
                ->with('error', 'Paiement introuvable.');
        }

        // Vérifier le statut réel auprès de FedaPay
        $verified = $fedaPay->verifyTransaction($paiement->gateway_transaction_id ?? '');

        if ($verified && $verified['status'] === 'approved') {
            if ($paiement->statut !== 'confirme') {
                $paiement->update([
                    'statut'         => 'confirme',
                    'gateway_status' => $verified['status'],
                    'paid_at'        => now(),
                ]);
                PaymentConfirmed::dispatch($paiement);
            }
            return redirect()->route('payment.success', ['paiement' => $paiement->id]);
        }

        if ($verified && in_array($verified['status'], ['declined', 'canceled'])) {
            $paiement->update(['statut' => 'echec', 'gateway_status' => $verified['status']]);
            return redirect()->route('payment.failed', ['paiement' => $paiement->id]);
        }

        // Statut encore en attente — rediriger vers la page d'attente
        return redirect()->route('payment.pending', ['paiement' => $paiement->id]);
    }

    /**
     * Callback Ajax POST depuis le widget KKiaPay.
     */
    public function kkiapay(Request $request, KKiaPayService $kkiaPay): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'transactionId' => 'required|string',
            'paiement_id'   => 'required|integer',
        ]);

        $paiement = $kkiaPay->handleCallback(
            $request->transactionId,
            (int) $request->paiement_id
        );

        if (!$paiement) {
            return response()->json(['success' => false, 'message' => 'Vérification échouée.'], 422);
        }

        if ($paiement->statut === 'confirme') {
            PaymentConfirmed::dispatch($paiement);
            $redirect = $this->successRedirect($paiement);
        } elseif ($paiement->statut === 'echec') {
            $redirect = route('payment.failed', ['paiement' => $paiement->id]);
        } else {
            $redirect = route('payment.pending', ['paiement' => $paiement->id]);
        }

        return response()->json(['success' => $paiement->statut === 'confirme', 'redirect' => $redirect]);
    }

    private function successRedirect(Paiement $paiement): string
    {
        return route('payment.success', ['paiement' => $paiement->id]);
    }
}
