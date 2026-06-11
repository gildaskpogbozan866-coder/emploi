<?php

namespace Database\Factories;

use App\Models\Langue;
use App\Models\NiveauLangue;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LangueCandidatFactory extends Factory
{
    public function definition(): array
    {
        return [
            'candidat_id' => User::factory()->candidat(),
            'langue_id'   => Langue::factory(),
            'niveau_id'   => NiveauLangue::factory(),
        ];
    }
}
