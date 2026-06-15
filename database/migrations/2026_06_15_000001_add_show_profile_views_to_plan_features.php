<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Plan gratuit candidat → vues cachées (0)
        $free = DB::table('plans')->where('slug', 'gratuit-candidat')->value('id');
        if ($free) {
            DB::table('plan_features')->updateOrInsert(
                ['plan_id' => $free, 'feature_key' => 'show_profile_views'],
                ['feature_value' => '0', 'created_at' => now(), 'updated_at' => now()]
            );
        }

        // Plan premium candidat → vues visibles (1)
        $premium = DB::table('plans')->where('slug', 'premium-candidat')->value('id');
        if ($premium) {
            DB::table('plan_features')->updateOrInsert(
                ['plan_id' => $premium, 'feature_key' => 'show_profile_views'],
                ['feature_value' => '1', 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }

    public function down(): void
    {
        DB::table('plan_features')
            ->where('feature_key', 'show_profile_views')
            ->delete();
    }
};
