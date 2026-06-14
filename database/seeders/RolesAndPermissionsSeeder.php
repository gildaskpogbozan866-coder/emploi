<?php

namespace Database\Seeders;

use App\Enums\Permission;
use App\Enums\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ── 1. Créer toutes les permissions ──────────────────
        foreach (Permission::all() as $permission) {
            SpatiePermission::firstOrCreate(['name' => $permission]);
        }

        // ── 2. Créer les rôles et leur associer les permissions ─
        $admin = SpatieRole::firstOrCreate(['name' => Role::ADMIN]);
        $admin->syncPermissions(Permission::adminPermissions());

        $recruteur = SpatieRole::firstOrCreate(['name' => Role::RECRUTEUR]);
        $recruteur->syncPermissions(Permission::recruteurPermissions());

        $candidat = SpatieRole::firstOrCreate(['name' => Role::CANDIDAT]);
        $candidat->syncPermissions(Permission::candidatPermissions());

        $annonceur = SpatieRole::firstOrCreate(['name' => Role::ANNONCEUR]);
        $annonceur->syncPermissions(Permission::annonceurPermissions());

        $this->command->info('✅ Rôles et permissions créés.');
    }
}
