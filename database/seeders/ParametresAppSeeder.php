<?php

namespace Database\Seeders;

use App\Models\ParametreApp;
use Illuminate\Database\Seeder;

class ParametresAppSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            [
                'cle'    => 'admin_notification_email',
                'valeur' => config('emploi.admin_notification_email', ''),
                'label'  => 'Email de réception des alertes admin',
            ],
        ];

        foreach ($defaults as $p) {
            ParametreApp::firstOrCreate(['cle' => $p['cle']], $p);
        }

        $this->command->info('✅ Paramètres app insérés.');
    }
}
