<?php

namespace App\Services;

use App\Models\Paiement;
use App\Models\PaymentSetting;
use FedaPay\FedaPay;
use FedaPay\Transaction;

class FedaPayService
{
    private ?PaymentSetting $settings;

    public function __construct()
    {
        $this->settings = PaymentSetting::forGateway('fedapay');
    }

    public function isAvailable(): bool
    {
        return $this->settings?->is_active
            && $this->settings->secret_key
            && $this->settings->public_key;
    }

    private function configure(): void
    {
        FedaPay::setApiKey($this->settings->secret_key);
        FedaPay::setEnvironment($this->settings->env === 'live' ? 'live' : 'sandbox');
    }

    /**
     * Crée une transaction FedaPay et retourne l'URL de paiement.
     */
    public function initiateTransaction(Paiement $paiement): string
    {
        $this->configure();

        $user = $paiement->user;

        $transaction = Transaction::create([
            'description' => $this->buildDescription($paiement),
            'amount'      => (int) $paiement->montant,
            'currency'    => ['iso' => $paiement->devise ?? 'XOF'],
            'callback_url'=> route('payment.callback.fedapay', ['paiement' => $paiement->id]),
            'customer'    => [
                'firstname' => $user->prenom ?? '',
                'lastname'  => $user->nom    ?? '',
                'email'     => $user->email,
                'phone_number' => [
                    'number'  => $user->telephone ?? '',
                    'country' => 'BJ',
                ],
            ],
        ]);

        // Stocker l'ID de transaction FedaPay
        $paiement->update([
            'gateway_transaction_id' => $transaction->id,
            'gateway_status'         => $transaction->status,
        ]);

        $token = $transaction->generateToken();
        return $token->url;
    }

    /**
     * Vérifie et traite un webhook FedaPay.
     * Retourne le Paiement mis à jour ou null si signature invalide.
     */
    public function handleWebhook(string $payload, string $signature): ?Paiement
    {
        // Vérification de la signature HMAC-SHA256
        $secret   = $this->settings?->webhook_secret;
        $expected = hash_hmac('sha256', $payload, $secret ?? '');
        if (!hash_equals($expected, $signature)) {
            return null;
        }

        $data = json_decode($payload, true);
        $event = $data['name'] ?? '';

        if (!in_array($event, ['transaction.approved', 'transaction.declined', 'transaction.canceled'])) {
            return null;
        }

        $transactionId = $data['data']['object']['id'] ?? null;
        if (!$transactionId) return null;

        $paiement = Paiement::where('gateway_transaction_id', $transactionId)->first();
        if (!$paiement) return null;

        $gatewayStatus = $data['data']['object']['status'] ?? '';
        $fees          = $data['data']['object']['fees'] ?? 0;

        $statut = match($event) {
            'transaction.approved' => 'confirme',
            'transaction.declined',
            'transaction.canceled' => 'echec',
            default                => $paiement->statut,
        };

        $paiement->update([
            'gateway_status' => $gatewayStatus,
            'gateway_fees'   => (int) $fees,
            'statut'         => $statut,
            'paid_at'        => $statut === 'confirme' ? now() : $paiement->paid_at,
        ]);

        return $paiement;
    }

    /**
     * Vérifie le statut d'une transaction auprès de FedaPay.
     */
    public function verifyTransaction(string $transactionId): ?array
    {
        $this->configure();

        try {
            $transaction = Transaction::retrieve($transactionId);
            return [
                'id'     => $transaction->id,
                'status' => $transaction->status,
                'amount' => $transaction->amount,
            ];
        } catch (\Throwable) {
            return null;
        }
    }

    private function buildDescription(Paiement $paiement): string
    {
        return match($paiement->type) {
            'cv_credits'           => "Achat de {$paiement->credits_cv} crédits CVthèque",
            'abonnement_recruteur' => "Abonnement recruteur",
            default                => "Paiement Emploi Bouge Bénin",
        };
    }
}
