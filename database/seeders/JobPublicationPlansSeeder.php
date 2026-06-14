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
                'name'          => '1 jour',
                'duration_days' => 1,
                'price'         => 1000,
                'is_free'       => false,
                'is_active'     => true,
            ],
            [
                'name'          => '1 semaine',
                'duration_days' => 7,
                'price'         => 6000,
                'is_free'       => false,
                'is_active'     => true,
            ],
            [
                'name'          => '1 mois',
                'duration_days' => 30,
                'price'         => 25000,
                'is_free'       => false,
                'is_active'     => true,
            ],
        ];

        // Supprimer les anciens plans avec des noms différents (migration des données)
        JobPublicationPlan::whereIn('name', ['3 jours', '30 jours', '365 jours', 'Illimité'])->delete();

        foreach ($plans as $plan) {
            JobPublicationPlan::updateOrCreate(
                ['duration_days' => $plan['duration_days']],
                $plan
            );
        }
    }
}
