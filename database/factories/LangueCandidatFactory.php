<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LangueCandidatFactory extends Factory
{
    public function definition(): array
    {
        return [
            'candidat_id' => User::factory()->candidat(),
            'langue'      => fake()->unique()->lexify('Langue-??????'),
            'niveau'      => fake()->randomElement(['A1', 'A2', 'B1', 'B2', 'C1', 'C2', 'natif']),
        ];
    }
}
