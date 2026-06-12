<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use App\Models\PaymentSetting;
use App\Services\FedaPayService;
use App\Services\KKiaPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        abort_if($paiement->statut !== 'en_attente', 404);

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
            $url = $this->fedaPay->initiateTransaction($paiement);
            return redirect()->away($url);
        }

        if ($gateway === 'kkiapay') {
            if (!$this->kkiaPay->isAvailable()) {
                return back()->with('error', 'KKiaPay n\'est pas disponible pour le moment.');
            }
            // KKiaPay utilise un widget JS — rediriger vers la page qui l'intègre
            return redirect()->route('payment.choose', ['paiement' => $paiement->id])
                ->with('launch_kkiapay', true);
        }

        // Manuel : laisser en_attente, informer l'utilisateur
        return redirect()->route('payment.pending', ['paiement' => $paiement->id]);
    }
}
