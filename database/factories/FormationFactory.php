<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormationFactory extends Factory
{
    public function definition(): array
    {
        $debut = fake()->dateTimeBetween('-8 years', '-3 years');
        $fin   = fake()->dateTimeBetween($debut, '-1 year');

        return [
            'candidat_id'  => User::factory()->candidat(),
            'diplome'      => fake()->randomElement(['Licence', 'Master', 'BTS', 'DUT', 'Doctorat', 'BAC']),
            'etablissement'=> fake()->randomElement(['UAC', 'UCAO', 'ENEAM', 'EPAC', 'FASEG']),
            'domaine'      => fake()->randomElement(['Informatique', 'Gestion', 'Droit', 'Médecine', 'Économie']),
            'date_debut'   => $debut->format('Y-m-d'),
            'date_fin'     => $fin->format('Y-m-d'),
            'en_cours'     => false,
            'activites'    => [fake()->sentence(), fake()->sentence()],
            'ordre'        => 0,
        ];
    }

    public function enCours(): static
    {
        return $this->state(fn () => [
            'date_fin' => null,
            'en_cours' => true,
        ]);
    }
}
