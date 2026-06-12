<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\PlanFeature;
use Illuminate\Database\Seeder;

class PlansSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name'          => 'Gratuit Candidat',
                'slug'          => 'gratuit-candidat',
                'description'   => 'Accès de base à la plateforme pour les candidats.',
                'target_type'   => 'candidat',
                'price'         => 0,
                'currency'      => 'FCFA',
                'duration_days' => null,
                'is_free'       => true,
                'is_active'     => true,
                'features'      => [
                    'cv_limit'         => '1',
                    'job_apply_limit'  => '10',
                    'candidate_search' => '0',
                    'featured_profile' => '0',
                ],
            ],
            [
                'name'          => 'Premium Candidat',
                'slug'          => 'premium-candidat',
                'description'   => 'Accès complet avec visibilité accrue et candidatures illimitées.',
                'target_type'   => 'candidat',
                'price'         => 2500,
                'currency'      => 'FCFA',
                'duration_days' => 30,
                'is_free'       => false,
                'is_active'     => true,
                'features'      => [
                    'cv_limit'         => '5',
                    'job_apply_limit'  => '100',
                    'candidate_search' => '0',
                    'featured_profile' => '1',
                ],
            ],
            [
                'name'          => 'Gratuit Recruteur',
                'slug'          => 'gratuit-recruteur',
                'description'   => 'Accès de base pour publier des offres d\'emploi.',
                'target_type'   => 'recruteur',
                'price'         => 0,
                'currency'      => 'FCFA',
                'duration_days' => null,
                'is_free'       => true,
                'is_active'     => true,
                'features'      => [
                    'job_post_limit'   => '2',
                    'candidate_search' => '0',
                    'featured_jobs'    => '0',
                ],
            ],
            [
                'name'          => 'Recruteur Pro',
                'slug'          => 'recruteur-pro',
                'description'   => 'Accès complet avec recherche de candidats et offres mises en avant.',
                'target_type'   => 'recruteur',
                'price'         => 10000,
                'currency'      => 'FCFA',
                'duration_days' => 30,
                'is_free'       => false,
                'is_active'     => true,
                'features'      => [
                    'job_post_limit'   => '10',
                    'candidate_search' => '1',
                    'featured_jobs'    => '5',
                ],
            ],
        ];

        foreach ($plans as $data) {
            $features = $data['features'];
            unset($data['features']);

            $plan = Plan::create($data);

            foreach ($features as $key => $value) {
                PlanFeature::create([
                    'plan_id'       => $plan->id,
                    'feature_key'   => $key,
                    'feature_value' => $value,
                ]);
            }
        }
    }
}
