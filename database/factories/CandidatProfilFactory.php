<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidatProfilFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'              => User::factory()->candidat(),
            'titre_professionnel'  => fake()->jobTitle(),
            'bio'                  => fake()->paragraph(),
            'ville'                => fake()->randomElement(['Cotonou', 'Porto-Novo', 'Abomey-Calavi', 'Parakou']),
            'disponibilite'        => fake()->randomElement(['immediatement', '1_mois', '2_mois', '3_mois', 'plus_3_mois']),
            'salaire_min'          => 100000,
            'salaire_max'          => 500000,
            'remote'               => fake()->randomElement(['non', 'partiel', 'total']),
            'linkedin'             => 'https://linkedin.com/in/' . fake()->userName(),
            'portfolio'            => null,
        ];
    }
}
