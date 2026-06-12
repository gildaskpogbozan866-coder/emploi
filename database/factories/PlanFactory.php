<?php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PlanFactory extends Factory
{
    protected $model = Plan::class;

    public function definition(): array
    {
        $isFree = $this->faker->boolean(30);
        $name   = $this->faker->words(2, true);

        return [
            'name'          => ucfirst($name),
            'slug'          => Str::slug($name) . '-' . $this->faker->unique()->numerify('###'),
            'description'   => $this->faker->sentence(),
            'target_type'   => $this->faker->randomElement(['candidat', 'recruteur', 'both']),
            'price'         => $isFree ? 0 : $this->faker->randomElement([2500, 5000, 10000, 25000]),
            'currency'      => 'FCFA',
            'duration_days' => $isFree ? null : $this->faker->randomElement([7, 30, 90, 365]),
            'is_free'       => $isFree,
            'is_active'     => true,
        ];
    }

    public function free(): static
    {
        return $this->state(fn () => [
            'price'         => 0,
            'is_free'       => true,
            'duration_days' => null,
        ]);
    }

    public function forCandidat(): static
    {
        return $this->state(fn () => ['target_type' => 'candidat']);
    }

    public function forRecruteur(): static
    {
        return $this->state(fn () => ['target_type' => 'recruteur']);
    }
}
