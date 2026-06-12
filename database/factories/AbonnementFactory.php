<?php

namespace Database\Factories;

use App\Models\Abonnement;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AbonnementFactory extends Factory
{
    protected $model = Abonnement::class;

    public function definition(): array
    {
        $plan     = Plan::inRandomOrder()->first() ?? Plan::factory()->create();
        $startsAt = $this->faker->dateTimeBetween('-3 months', 'now');
        $endsAt   = $plan->duration_days
            ? (clone $startsAt)->modify("+{$plan->duration_days} days")
            : null;

        return [
            'user_id'    => User::factory(),
            'plan_id'    => $plan->id,
            'starts_at'  => $startsAt,
            'ends_at'    => $endsAt,
            'status'     => 'active',
            'auto_renew' => false,
        ];
    }

    public function active(): static
    {
        return $this->state(fn () => ['status' => 'active']);
    }

    public function expired(): static
    {
        return $this->state(fn () => [
            'status'  => 'expired',
            'ends_at' => now()->subDay(),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn () => ['status' => 'cancelled']);
    }
}
