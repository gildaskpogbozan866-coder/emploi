<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use App\Services\FedaPayService;
use App\Services\KKiaPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GatewayController extends Controller
{
    public function __construct(
        private FedaPayService $fedaPay,
        private KKiaPayService $kkiaPay,
    ) {}

    /**
     * Vérifie l'accès : utilisateur connecté OU token dans l'URL/session.
     * Stocke le token en session pour les étapes suivantes.
     */
    private function verifyAccess(Paiement $paiement): void
    {
        if (auth()->check()) {
            abort_if($paiement->user_id !== auth()->id(), 403);
            return;
        }

        $token = request('token') ?? session('pay_access_' . $paiement->id);
        abort_if($token !== $paiement->reference, 403);

        session(['pay_access_' . $paiement->id => $paiement->reference]);
    }

    /**
     * Page de choix du mode de paiement.
     */
    public function choose(Paiement $paiement)
    {
        $this->verifyAccess($paiement);
        abort_if(!in_array($paiement->statut, ['en_attente', 'echec']), 404);

        if ($paiement->statut === 'echec') {
            $paiement->update([
                'statut'                 => 'en_attente',
                'gateway'                => null,
                'gateway_transaction_id' => null,
                'gateway_status'         => null,
            ]);
        }

        // KKiaPay prioritaire pour Mobile Money, FedaPay en fallback.
        $mobileVia = $this->kkiaPay->isAvailable() ? 'kkiapay'
                   : ($this->fedaPay->isAvailable() ? 'fedapay' : null);

        $cardVia = $this->fedaPay->isAvailable() ? 'fedapay' : null;

        $kkiaConfig = ($mobileVia === 'kkiapay')
            ? $this->kkiaPay->widgetConfig($paiement)
            : null;

        if ($paiement->type === 'service') {
            $paiement->load('payable.service');
        }

        return view('payment.choose', compact('paiement', 'mobileVia', 'cardVia', 'kkiaConfig'));
    }

    /**
     * Lance le paiement FedaPay.
     * (KKiaPay est géré entièrement côté JS + CallbackController::kkiapay)
     */
    public function initiate(Request $request, Paiement $paiement)
    {
        $this->verifyAccess($paiement);
        abort_if($paiement->statut !== 'en_attente', 404);

        $request->validate([
            'gateway' => 'required|in:fedapay',
            'methode' => 'nullable|string|max:50',
        ]);

        $paiement->update([
            'gateway' => 'fedapay',
            'methode' => $request->methode ?? 'fedapay',
        ]);

        if (!$this->fedaPay->isAvailable()) {
            return back()->with('error', 'Le paiement en ligne est indisponible pour le moment.');
        }

        try {
            $url = $this->fedaPay->initiateTransaction($paiement);
        } catch (\Throwable $e) {
            Log::error('FedaPay initiate error', [
                'paiement_id' => $paiement->id,
                'message'     => $e->getMessage(),
            ]);
            return back()->with('error', 'Impossible d\'initier le paiement : ' . $e->getMessage());
        }

        return redirect()->away($url);
    }
}
