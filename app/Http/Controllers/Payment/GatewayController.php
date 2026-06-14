<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use App\Models\PaymentSetting;
use App\Services\FedaPayService;
use App\Services\KKiaPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GatewayController extends Controller
{
    public function __construct(
        private FedaPayService $fedaPay,
        private KKiaPayService $kkiaPay,
    ) {}

    /**
     * Page de choix du mode de paiement pour un paiement existant.
     */
    public function choose(Paiement $paiement)
    {
        abort_if($paiement->user_id !== Auth::id(), 403);
        abort_if(!in_array($paiement->statut, ['en_attente', 'echec']), 404);

        if ($paiement->statut === 'echec') {
            $paiement->update([
                'statut'                 => 'en_attente',
                'gateway'                => 'manuel',
                'gateway_transaction_id' => null,
                'gateway_status'         => null,
            ]);
        }

        $gateways = [
            'fedapay' => [
                'available' => $this->fedaPay->isAvailable(),
                'label'     => 'FedaPay',
                'subtitle'  => 'Mobile Money (MTN, Moov, Celtiis) · Carte bancaire',
                'logo'      => 'fedapay',
            ],
            'kkiapay' => [
                'available' => $this->kkiaPay->isAvailable(),
                'label'     => 'KKiaPay',
                'subtitle'  => 'Mobile Money (MTN, Moov, Celtiis)',
                'logo'      => 'kkiapay',
            ],
            'manuel'  => [
                'available' => true,
                'label'     => 'Virement / Manuel',
                'subtitle'  => 'Un conseiller vous contactera sous 24h',
                'logo'      => 'manuel',
            ],
        ];

        $kkiaConfig = $this->kkiaPay->isAvailable()
            ? $this->kkiaPay->widgetConfig($paiement)
            : null;

        if ($paiement->type === 'service') {
            $paiement->load('payable.service');
        }

        return view('payment.choose', compact('paiement', 'gateways', 'kkiaConfig'));
    }

    /**
     * Lance le paiement via le gateway sélectionné.
     */
    public function initiate(Request $request, Paiement $paiement)
    {
        abort_if($paiement->user_id !== Auth::id(), 403);
        abort_if($paiement->statut !== 'en_attente', 404);

        $request->validate(['gateway' => 'required|in:fedapay,kkiapay,manuel']);
        $gateway = $request->gateway;

        $paiement->update(['gateway' => $gateway]);

        if ($gateway === 'fedapay') {
            if (!$this->fedaPay->isAvailable()) {
                return back()->with('error', 'FedaPay n\'est pas disponible pour le moment.');
            }
            try {
                $url = $this->fedaPay->initiateTransaction($paiement);
            } catch (\Throwable $e) {
                Log::error('FedaPay initiate error', [
                    'paiement_id' => $paiement->id,
                    'message'     => $e->getMessage(),
                ]);
                return back()->with('error', 'Impossible d\'initier le paiement FedaPay : ' . $e->getMessage());
            }
            return redirect()->away($url);
        }

        if ($gateway === 'kkiapay') {
            // KKiaPay s'initialise via son widget JS sur la page de choix
            return redirect()->route('payment.choose', ['paiement' => $paiement->id]);
        }

        // Manuel : laisser en_attente, informer l'utilisateur
        return redirect()->route('payment.pending', ['paiement' => $paiement->id]);
    }
}
