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

        $customer = [
            'firstname' => $user->prenom ?: 'Client',
            'lastname'  => $user->nom    ?: 'Inconnu',
            'email'     => $user->email,
        ];

        // FedaPay refuse un numéro vide ou mal formaté — on ne l'inclut que s'il est valide
        $phone = $this->sanitizePhone($user->telephone ?? '');
        if ($phone !== '') {
            $customer['phone_number'] = ['number' => $phone, 'country' => 'BJ'];
        }

        $transaction = Transaction::create([
            'description' => $this->buildDescription($paiement),
            'amount'      => (int) $paiement->montant,
            'currency'    => ['iso' => 'XOF'],
            'callback_url'=> route('payment.callback.fedapay', ['paiement' => $paiement->id]),
            'customer'    => $customer,
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

    /**
     * Vérifie la signature HMAC d'un webhook FedaPay.
     * Format header : "t=timestamp,v1=hash"
     */
    public function verifyWebhookSignature(string $payload, string $signatureHeader, string $secret): bool
    {
        $parts = [];
        foreach (explode(',', $signatureHeader) as $part) {
            [$key, $val] = array_pad(explode('=', $part, 2), 2, '');
            $parts[trim($key)] = trim($val);
        }

        if (empty($parts['v1'])) {
            return false;
        }

        $signed            = ($parts['t'] ?? '') . '.' . $payload;
        $expectedSignature = hash_hmac('sha256', $signed, $secret);

        return hash_equals($expectedSignature, $parts['v1']);
    }

    /**
     * Retire le préfixe international du numéro béninois pour FedaPay.
     * FedaPay attend le numéro local uniquement (ex: 97000000, pas +22997000000).
     */
    private function sanitizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone); // garder chiffres seulement
        if (str_starts_with($phone, '00229')) {
            $phone = substr($phone, 5);
        } elseif (str_starts_with($phone, '229')) {
            $phone = substr($phone, 3);
        }
        return strlen($phone) >= 8 ? $phone : '';
    }

    private function buildDescription(Paiement $paiement): string
    {
        return match($paiement->type) {
            'cv_credits'           => "Achat de {$paiement->credits_cv} crédits CVthèque",
            'abonnement_recruteur' => "Abonnement recruteur",
            'abonnement_candidat'  => "Abonnement candidat",
            default                => "Paiement Emploi Bouge Bénin",
        };
    }
}
