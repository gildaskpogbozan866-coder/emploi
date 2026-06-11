<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CompetenceFactory extends Factory
{
    public function definition(): array
    {
        $words = fake()->unique()->words(2, true);
        return [
            'nom'  => ucfirst($words),
            'slug' => Str::slug($words),
        ];
    }
}
