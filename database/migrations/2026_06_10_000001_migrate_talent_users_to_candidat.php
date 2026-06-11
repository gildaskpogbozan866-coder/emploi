<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Colonne role sur users ────────────────────────
        DB::table('users')
            ->where('role', 'talent')
            ->update(['role' => 'candidat']);

        // ── 2. Spatie model_has_roles ────────────────────────
        $talentRoleId   = DB::table('roles')->where('name', 'talent')->value('id');
        $candidatRoleId = DB::table('roles')->where('name', 'candidat')->value('id');

        if (! $talentRoleId || ! $candidatRoleId) {
            return; // les rôles Spatie n'existent pas encore (fresh install)
        }

        $talentUserIds = DB::table('model_has_roles')
            ->where('role_id', $talentRoleId)
            ->where('model_type', 'App\\Models\\User')
            ->pluck('model_id');

        foreach ($talentUserIds as $userId) {
            $alreadyCandidat = DB::table('model_has_roles')
                ->where('role_id', $candidatRoleId)
                ->where('model_type', 'App\\Models\\User')
                ->where('model_id', $userId)
                ->exists();

            if (! $alreadyCandidat) {
                DB::table('model_has_roles')->insert([
                    'role_id'    => $candidatRoleId,
                    'model_type' => 'App\\Models\\User',
                    'model_id'   => $userId,
                ]);
            }

            // Supprimer l'ancien rôle talent
            DB::table('model_has_roles')
                ->where('role_id', $talentRoleId)
                ->where('model_type', 'App\\Models\\User')
                ->where('model_id', $userId)
                ->delete();
        }
    }

    public function down(): void
    {
        // Irréversible — impossible de savoir qui était talent
    }
};
