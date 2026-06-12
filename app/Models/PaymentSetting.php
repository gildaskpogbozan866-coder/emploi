<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class PaymentSetting extends Model
{
    protected $fillable = [
        'gateway', 'env', 'public_key', 'secret_key', 'webhook_secret', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function forGateway(string $gateway): ?self
    {
        return static::where('gateway', $gateway)->first();
    }

    public function getSecretKeyAttribute(?string $value): ?string
    {
        if (!$value) return null;
        try { return Crypt::decryptString($value); } catch (\Throwable) { return $value; }
    }

    public function setSecretKeyAttribute(?string $value): void
    {
        $this->attributes['secret_key'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getWebhookSecretAttribute(?string $value): ?string
    {
        if (!$value) return null;
        try { return Crypt::decryptString($value); } catch (\Throwable) { return $value; }
    }

    public function setWebhookSecretAttribute(?string $value): void
    {
        $this->attributes['webhook_secret'] = $value ? Crypt::encryptString($value) : null;
    }
}
