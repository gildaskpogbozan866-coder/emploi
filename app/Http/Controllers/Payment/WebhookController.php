<?php

namespace App\Http\Controllers\Payment;

use App\Events\PaymentConfirmed;
use App\Http\Controllers\Controller;
use App\Services\FedaPayService;
use App\Services\KKiaPayService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WebhookController extends Controller
{
    public function fedapay(Request $request, FedaPayService $fedaPay): Response
    {
        $payload   = $request->getContent();
        $signature = $request->header('X-FEDAPAY-SIGNATURE', '');

        $paiement = $fedaPay->handleWebhook($payload, $signature);

        if (!$paiement) {
            return response('Unauthorized', 401);
        }

        if ($paiement->statut === 'confirme') {
            PaymentConfirmed::dispatch($paiement);
        }

        return response('OK', 200);
    }

    public function kkiapay(Request $request, KKiaPayService $kkiaPay): Response
    {
        $payload   = $request->getContent();
        $signature = $request->header('x-kkiapay-signature', '');

        $paiement = $kkiaPay->handleWebhook($payload, $signature);

        if (!$paiement) {
            return response('Unauthorized', 401);
        }

        if ($paiement->statut === 'confirme') {
            PaymentConfirmed::dispatch($paiement);
        }

        return response('OK', 200);
    }
}
