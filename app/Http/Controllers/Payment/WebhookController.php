<?php

namespace App\Http\Controllers\Payment;

use App\Events\PaymentConfirmed;
use App\Http\Controllers\Controller;
use App\Models\Paiement;
use App\Models\PaymentSetting;
use App\Services\FedaPayService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WebhookController extends Controller
{
    /**
     * Webhook push serveur-à-serveur FedaPay.
     * Route CSRF-exempt (voir bootstrap/app.php).
     */
    public function fedapay(Request $request, FedaPayService $fedaPay): Response
    {
        $setting = PaymentSetting::forGateway('fedapay');

        if ($setting?->webhook_secret) {
            $signature = $request->header('X-Fedapay-Signature', '');
            if (!$fedaPay->verifyWebhookSignature($request->getContent(), $signature, $setting->webhook_secret)) {
                return response('Signature invalide.', 403);
            }
        }

        $payload = $request->json()->all();
        $event   = $payload['name'] ?? null;
        $object  = $payload['data']['object'] ?? null;

        if (!$event || !$object) {
            return response('Payload invalide.', 400);
        }

        $transactionId = $object['id'] ?? null;
        $status        = $object['status'] ?? null;

        if (!$transactionId) {
            return response('OK', 200);
        }

        $paiement = Paiement::where('gateway_transaction_id', (string) $transactionId)
            ->where('gateway', 'fedapay')
            ->first();

        if (!$paiement) {
            return response('OK', 200);
        }

        if ($status === 'approved' && $paiement->statut !== 'confirme') {
            $paiement->update([
                'statut'         => 'confirme',
                'gateway_status' => $status,
                'paid_at'        => now(),
            ]);
            PaymentConfirmed::dispatch($paiement);
        }

        if (in_array($status, ['declined', 'canceled']) && $paiement->statut === 'en_attente') {
            $paiement->update([
                'statut'         => 'echec',
                'gateway_status' => $status,
            ]);
        }

        return response('OK', 200);
    }
}
