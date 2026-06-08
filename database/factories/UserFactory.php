<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'prenom'            => fake()->firstName(),
            'nom'               => fake()->lastName(),
            'email'             => fake()->unique()->safeEmail(),
            'password'          => static::$password ??= Hash::make('password'),
            'tel'               => fake()->numerify('+229 ## ## ## ##'),
            'pays'              => 'Bénin',
            'role'              => 'candidat',
            'email_verified_at' => now(),
            'actif'             => true,
            'remember_token'    => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn () => ['email_verified_at' => null]);
    }

    public function inactif(): static
    {
        return $this->state(fn () => ['actif' => false]);
    }

    public function candidat(): static
    {
        return $this->state(fn () => ['role' => 'candidat']);
    }

    public function recruteur(): static
    {
        return $this->state(fn () => [
            'role'       => 'recruteur',
            'entreprise' => fake()->company(),
        ]);
    }

    public function talent(): static
    {
        return $this->state(fn () => [
            'role'   => 'talent',
            'metier' => fake()->jobTitle(),
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn () => ['role' => 'admin']);
    }
}
