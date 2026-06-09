<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExperienceFactory extends Factory
{
    public function definition(): array
    {
        $debut = fake()->dateTimeBetween('-6 years', '-2 years');
        $fin   = fake()->dateTimeBetween($debut, '-6 months');

        return [
            'candidat_id' => User::factory()->candidat(),
            'poste'       => fake()->jobTitle(),
            'entreprise'  => fake()->company(),
            'lieu'        => fake()->city() . ', Bénin',
            'secteur'     => fake()->randomElement(['Informatique', 'Finance', 'Commerce', 'Agriculture', 'Santé']),
            'date_debut'  => $debut->format('Y-m-d'),
            'date_fin'    => $fin->format('Y-m-d'),
            'en_cours'    => false,
            'missions'    => [fake()->sentence(), fake()->sentence()],
            'ordre'       => 0,
        ];
    }

    public function enCours(): static
    {
        return $this->state(fn () => [
            'date_fin'  => null,
            'en_cours'  => true,
        ]);
    }
}
