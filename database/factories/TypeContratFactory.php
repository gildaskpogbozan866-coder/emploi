<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TypeContratFactory extends Factory
{
    public function definition(): array
    {
        $code = fake()->unique()->randomElement(['CDI', 'CDD', 'Stage', 'Freelance', 'Temps partiel', 'Bourse']);

        return [
            'code'    => $code,
            'libelle' => $code,
        ];
    }
}
