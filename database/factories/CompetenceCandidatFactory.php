<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompetenceCandidatFactory extends Factory
{
    public function definition(): array
    {
        return [
            'candidat_id' => User::factory()->candidat(),
            'nom'         => fake()->unique()->lexify('Comp-??????'),
            'niveau'      => fake()->randomElement(['debutant', 'intermediaire', 'avance', 'expert']),
        ];
    }
}
