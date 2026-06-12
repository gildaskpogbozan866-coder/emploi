<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Garantit que les rôles et permissions existent
        $this->call(RolesAndPermissionsSeeder::class);

        $admin = User::updateOrCreate(
            ['email' => 'admin@emploibougebenin.com'],
            [
                'prenom'            => 'Super',
                'nom'               => 'Admin',
                'password'          => Hash::make('
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                '),
                'role'              => Role::ADMIN,
                'pays'              => 'Bénin',
                'actif'             => true,
                'email_verified_at' => now(),
            ]
        );

        $admin->syncRoles([Role::ADMIN]);

        $this->command->info('✅ Compte admin créé/mis à jour.');
        $this->command->line('   Email    : admin@emploibougebenin.com');
        $this->command->line('   Password : Admin@2026');
    }
}
