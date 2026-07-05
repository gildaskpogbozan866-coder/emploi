<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * Artisan::call('down'/'up') agit sur le vrai fichier de maintenance du
 * disque (storage/framework/maintenance.php), partagé avec l'environnement
 * de dev local — chaque test remet impérativement le site "up" en fin de
 * test (tearDown), même en cas d'échec, pour ne jamais laisser le poste de
 * dev bloqué en maintenance après un run de tests.
 */
class MaintenanceModeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        Cache::flush();
    }

    protected function tearDown(): void
    {
        Artisan::call('up');
        parent::tearDown();
    }

    private function creerAdmin(): User
    {
        $user = User::factory()->create(['role' => 'admin']);
        $user->assignRole('admin');
        return $user;
    }

    public function test_admin_peut_activer_le_mode_maintenance(): void
    {
        $admin = $this->creerAdmin();

        $this->actingAs($admin)
            ->post(route('admin.parametres.maintenance.activer'))
            ->assertRedirect(route('admin.parametres'));

        $this->assertTrue(app()->isDownForMaintenance());
    }

    public function test_visiteur_public_voit_503_pendant_la_maintenance(): void
    {
        $admin = $this->creerAdmin();
        $this->actingAs($admin)->post(route('admin.parametres.maintenance.activer'));

        $this->get(route('home'))->assertStatus(503);
    }

    public function test_espace_admin_reste_accessible_pendant_la_maintenance(): void
    {
        $admin = $this->creerAdmin();
        $this->actingAs($admin)->post(route('admin.parametres.maintenance.activer'));

        // L'admin ne se retrouve jamais bloqué hors de son propre espace.
        $this->actingAs($admin)
            ->get(route('admin.parametres'))
            ->assertOk();
    }

    public function test_page_de_connexion_reste_accessible_pendant_la_maintenance(): void
    {
        $admin = $this->creerAdmin();
        $this->actingAs($admin)->post(route('admin.parametres.maintenance.activer'));

        // Scénario réel visé : un admin déconnecté (session expirée) doit
        // pouvoir se reconnecter, pas juste rester connecté toute la maintenance.
        $this->post(route('auth.deconnecter'));
        $this->get(route('auth.connexion'))->assertOk();
    }

    public function test_admin_peut_desactiver_le_mode_maintenance(): void
    {
        $admin = $this->creerAdmin();
        $this->actingAs($admin)->post(route('admin.parametres.maintenance.activer'));
        $this->assertTrue(app()->isDownForMaintenance());

        $this->actingAs($admin)
            ->post(route('admin.parametres.maintenance.desactiver'))
            ->assertRedirect(route('admin.parametres'));

        $this->assertFalse(app()->isDownForMaintenance());

        $this->get(route('home'))->assertOk();
    }
}
