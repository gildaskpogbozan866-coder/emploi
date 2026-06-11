<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NiveauLangueFactory extends Factory
{
    public function definition(): array
    {
        $num = fake()->unique()->numerify('##');
        return [
            'code'    => 'N' . $num,
            'libelle' => 'Niveau N' . $num,
            'ordre'   => (int) $num,
        ];
    }
}
