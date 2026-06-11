<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OffreFactory extends Factory
{
    public function definition(): array
    {
        return [
            'recruteur_id' => User::factory()->recruteur(),
            'titre'        => fake()->jobTitle(),
            'entreprise'   => fake()->company(),
            'localisation' => 'Cotonou, Bénin',
            'type'         => fake()->randomElement(['CDI','CDD','Stage','Freelance']),
            'secteur'      => fake()->randomElement(['Informatique','Finance','Commerce']),
            'description'  => fake()->paragraphs(3, true),
            'statut'       => 'active',
            'vues'         => 0,
        ];
    }

    public function active(): static
    {
        return $this->state(fn () => ['statut' => 'active']);
    }

    public function clos(): static
    {
        return $this->state(fn () => ['statut' => 'clos']);
    }

    public function expire(): static
    {
        return $this->state(fn () => [
            'statut'      => 'expiree',
            'date_limite' => now()->subDay(),
        ]);
    }
}
