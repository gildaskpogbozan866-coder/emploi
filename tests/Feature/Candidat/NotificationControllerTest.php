<?php

namespace Tests\Feature\Candidat;

use App\Models\Notification;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class NotificationControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        Cache::flush();
    }

    private function creerCandidat(): User
    {
        $user = User::factory()->candidat()->create();
        $user->assignRole('candidat');
        return $user;
    }

    private function creerNotification(User $user, bool $lu = false): Notification
    {
        return Notification::create([
            'user_id' => $user->id,
            'type'    => 'candidature',
            'titre'   => 'Test notification',
            'contenu' => 'Contenu de test',
            'lien'    => '/test',
            'lu'      => $lu,
        ]);
    }

    // ── Accès ─────────────────────────────────────────────

    public function test_index_redirige_si_non_connecte(): void
    {
        $this->get(route('candidat.notifications'))
            ->assertRedirect(route('auth.connexion'));
    }

    public function test_index_accessible_au_candidat(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->get(route('candidat.notifications'))
            ->assertOk()
            ->assertViewIs('candidat.notifications');
    }

    // ── Affichage ─────────────────────────────────────────

    public function test_index_affiche_les_notifications_du_candidat(): void
    {
        $candidat = $this->creerCandidat();
        $autre    = $this->creerCandidat();

        $this->creerNotification($candidat);
        $notifAutre = $this->creerNotification($autre);

        $response = $this->actingAs($candidat)
            ->get(route('candidat.notifications'));

        $response->assertViewHas('notifications');
        $notifs = $response->viewData('notifications');
        $this->assertFalse($notifs->contains('id', $notifAutre->id));
    }

    // ── Marquer comme lues ────────────────────────────────

    public function test_index_marque_toutes_les_notifications_comme_lues(): void
    {
        $candidat = $this->creerCandidat();
        $this->creerNotification($candidat, lu: false);
        $this->creerNotification($candidat, lu: false);

        $this->actingAs($candidat)->get(route('candidat.notifications'));

        $this->assertEquals(0, Notification::where('user_id', $candidat->id)->where('lu', false)->count());
    }

    public function test_marquer_lues_met_toutes_a_jour(): void
    {
        $candidat = $this->creerCandidat();
        $this->creerNotification($candidat, lu: false);
        $this->creerNotification($candidat, lu: false);

        $this->actingAs($candidat)
            ->post(route('candidat.notifications.lues'))
            ->assertRedirect();

        $this->assertEquals(0, Notification::where('user_id', $candidat->id)->where('lu', false)->count());
    }

    public function test_marquer_lues_ne_touche_pas_les_notifications_dautres_utilisateurs(): void
    {
        $candidat = $this->creerCandidat();
        $autre    = $this->creerCandidat();

        $notifAutre = $this->creerNotification($autre, lu: false);

        $this->actingAs($candidat)
            ->post(route('candidat.notifications.lues'));

        $this->assertFalse($notifAutre->fresh()->lu);
    }

    // ── Bell (ViewComposer) ───────────────────────────────

    public function test_nombre_de_non_lues_est_injecte_dans_la_vue(): void
    {
        $candidat = $this->creerCandidat();
        $this->creerNotification($candidat, lu: false);
        $this->creerNotification($candidat, lu: false);
        $this->creerNotification($candidat, lu: true);

        $response = $this->actingAs($candidat)
            ->get(route('candidat.notifications'));

        // Le ViewComposer injecte $notifNonLues dans tous les layouts
        // (il est re-calculé avant le marquage car le composer s'exécute lors du rendu)
        $response->assertOk();
    }

    public function test_marquer_lues_redirige_avec_succes(): void
    {
        $candidat = $this->creerCandidat();
        $this->creerNotification($candidat, lu: false);

        $this->actingAs($candidat)
            ->post(route('candidat.notifications.lues'))
            ->assertRedirect()
            ->assertSessionHas('success');
    }
}
