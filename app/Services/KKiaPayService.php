<?php

namespace App\Services;

use App\Models\Paiement;
use App\Models\PaymentSetting;
use Illuminate\Support\Facades\Http;

class KKiaPayService
{
    private const API_BASE = 'https://api.kkiapay.me/api/v1';

    private ?PaymentSetting $settings;

    public function __construct()
    {
        $this->settings = PaymentSetting::forGateway('kkiapay');
    }

    public function isAvailable(): bool
    {
        return $this->settings?->is_active
            && $this->settings->secret_key
            && $this->settings->public_key;
    }

    /**
     * Retourne la config nécessaire au widget JS KKiaPay.
     */
    public function widgetConfig(Paiement $paiement): array
    {
        return [
            'publicApiKey' => $this->settings->public_key,
            'amount'       => (int) $paiement->montant,
            'name'         => $paiement->user->prenom . ' ' . $paiement->user->nom,
            'email'        => $paiement->user->email,
            'phone'        => $paiement->user->telephone ?? '',
            'data'         => ['paiement_id' => $paiement->id],
            'callback'     => route('payment.callback.kkiapay'),
            'sandbox'      => $this->settings->env !== 'live',
            'theme'        => '#042C53',
        ];
    }

    /**
     * Vérifie côté serveur qu'une transaction KKiaPay est bien approuvée.
     */
    public function verifyTransaction(string $transactionId): ?array
    {
        $response = Http::withHeaders([
            'x-private-key' => $this->settings->secret_key,
        ])->get(self::API_BASE . "/transactions/{$transactionId}/status");

        if (!$response->successful()) {
            return null;
        }

        $data = $response->json();
        return [
            'id'     => $transactionId,
            'status' => $data['status'] ?? 'unknown',
            'amount' => $data['amount'] ?? 0,
        ];
    }

    /**
     * Traite un callback KKiaPay (depuis le widget JS via Ajax ou depuis le webhook).
     * Retourne le Paiement mis à jour ou null.
     */
    public function handleCallback(string $transactionId, int $paiementId): ?Paiement
    {
        $verified = $this->verifyTransaction($transactionId);
        if (!$verified) return null;

        $paiement = Paiement::find($paiementId);
        if (!$paiement) return null;

        $statut = match($verified['status']) {
            'SUCCESS'           => 'confirme',
            'FAILED', 'REFUND'  => 'echec',
            default             => 'en_attente',
        };

        $paiement->update([
            'gateway_transaction_id' => $transactionId,
            'gateway_status'         => $verified['status'],
            'statut'                 => $statut,
            'paid_at'                => $statut === 'confirme' ? now() : $paiement->paid_at,
        ]);

        return $paiement;
    }

    /**
     * Vérifie et traite un webhook KKiaPay.
     */
    public function handleWebhook(string $payload, string $signature): ?Paiement
    {
        $secret   = $this->settings?->webhook_secret;
        $expected = hash_hmac('sha256', $payload, $secret ?? '');
        if (!hash_equals($expected, $signature)) {
            return null;
        }

        $data          = json_decode($payload, true);
        $transactionId = $data['transactionId'] ?? null;
        $paiementId    = $data['data']['paiement_id'] ?? null;

        if (!$transactionId || !$paiementId) return null;

        return $this->handleCallback($transactionId, $paiementId);
    }
}
