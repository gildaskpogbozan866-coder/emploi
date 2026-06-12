<?php

namespace Database\Seeders;

use App\Models\JobPublicationPlan;
use Illuminate\Database\Seeder;

class JobPublicationPlansSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name'          => '3 jours',
                'duration_days' => 3,
                'price'         => 0,
                'is_free'       => true,
                'is_active'     => true,
            ],
            [
                'name'          => '30 jours',
                'duration_days' => 30,
                'price'         => 1000,
                'is_free'       => false,
                'is_active'     => true,
            ],
            [
                'name'          => '365 jours',
                'duration_days' => 365,
                'price'         => 5000,
                'is_free'       => false,
                'is_active'     => true,
            ],
            [
                'name'          => 'Illimité',
                'duration_days' => null, // expires_at restera null à la publication
                'price'         => 15000,
                'is_free'       => false,
                'is_active'     => true,
            ],
        ];

        foreach ($plans as $plan) {
            JobPublicationPlan::create($plan);
        }
    }
}
