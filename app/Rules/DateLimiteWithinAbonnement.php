<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Carbon;

/** La date limite de candidature d'une offre ne peut pas dépasser la fin de l'abonnement actif du recruteur. */
class DateLimiteWithinAbonnement implements ValidationRule
{
    public function __construct(private readonly ?User $user) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $finAbonnement = $this->user?->abonnementActif()->first()?->ends_at;

        if ($finAbonnement !== null && Carbon::parse($value)->gt($finAbonnement)) {
            $fail("La date limite de candidature ne peut pas dépasser la date de fin de votre abonnement ({$finAbonnement->format('d/m/Y')}).");
        }
    }
}
