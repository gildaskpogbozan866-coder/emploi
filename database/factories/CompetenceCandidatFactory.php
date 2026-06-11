<?php

namespace Database\Factories;

use App\Models\Competence;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompetenceCandidatFactory extends Factory
{
    public function definition(): array
    {
        return [
            'candidat_id'      => User::factory()->candidat(),
            'competence_id'    => Competence::factory(),
            'annees_experience' => fake()->optional()->numberBetween(0, 20),
        ];
    }
}
