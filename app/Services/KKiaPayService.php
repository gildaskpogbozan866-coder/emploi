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
            && $this->settings->public_key
            && $this->settings->private_key;
    }

    /**
     * Retourne la config nécessaire au widget JS KKiaPay.
     * On ne passe PAS de "callback" car on utilise addSuccessListener côté JS
     * (callback = redirection navigateur GET, incompatible avec notre route POST).
     */
    public function widgetConfig(Paiement $paiement): array
    {
        return [
            'publicApiKey' => $this->settings->public_key,
            'amount'       => (int) $paiement->montant,
            'name'         => trim(($paiement->user->prenom ?? '') . ' ' . ($paiement->user->nom ?? '')),
            'email'        => $paiement->user->email,
            'phone'        => $paiement->user->telephone ?? '',
            'data'         => ['paiement_id' => $paiement->id],
            'sandbox'      => $this->settings->env !== 'live',
            'theme'        => '#042C53',
        ];
    }

    /**
     * Vérifie côté serveur qu'une transaction KKiaPay est bien approuvée.
     * Utilise la private_key (clé dédiée à la vérification, distincte de la secret_key).
     */
    public function verifyTransaction(string $transactionId): ?array
    {
        $response = Http::withHeaders([
            'x-private-key' => $this->settings->private_key,
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
     * Traite un callback KKiaPay (depuis le widget JS via addSuccessListener).
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
            'gateway'                => 'kkiapay',
            'gateway_transaction_id' => $transactionId,
            'gateway_status'         => $verified['status'],
            'statut'                 => $statut,
            'paid_at'                => $statut === 'confirme' ? now() : $paiement->paid_at,
        ]);

        return $paiement;
    }
}
