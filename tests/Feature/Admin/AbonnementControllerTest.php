<?php

namespace Tests\Feature\Admin;

use App\Models\Abonnement;
use App\Models\Notification as NotificationModel;
use App\Models\Plan;
use App\Models\User;
use App\Notifications\AbonnementActiveNotification;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AbonnementControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        Cache::flush();
    }

    // ── Helpers ───────────────────────────────────────────

    private function creerAdmin(): User
    {
        $user = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
        $user->assignRole('admin');
        return $user;
    }

    private function creerCandidat(): User
    {
        $user = User::factory()->candidat()->create();
        $user->assignRole('candidat');
        return $user;
    }

    private function creerRecruteur(): User
    {
        $user = User::factory()->recruteur()->create();
        $user->assignRole('recruteur');
        return $user;
    }

    private function creerPlan(array $attrs = []): Plan
    {
        return Plan::factory()->create(array_merge([
            'is_active' => true,
        ], $attrs));
    }

    // ── Tests ─────────────────────────────────────────────

    public function test_store_interdit_a_un_non_admin(): void
    {
        $candidat = $this->creerCandidat();
        $plan     = $this->creerPlan(['target_type' => 'candidat']);

        $this->actingAs($candidat)
            ->post(route('admin.abonnements.store'), [
                'email'   => $candidat->email,
                'plan_id' => $plan->id,
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('abonnements', 0);
    }

    public function test_admin_active_un_abonnement_pour_un_candidat(): void
    {
        Notification::fake();

        $admin    = $this->creerAdmin();
        $candidat = $this->creerCandidat();
        $plan     = $this->creerPlan(['target_type' => 'candidat', 'duration_days' => 30]);

        $this->actingAs($admin)
            ->post(route('admin.abonnements.store'), [
                'email'   => $candidat->email,
                'plan_id' => $plan->id,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('abonnements', [
            'user_id' => $candidat->id,
            'plan_id' => $plan->id,
            'status'  => 'active',
        ]);

        $abonnement = Abonnement::where('user_id', $candidat->id)->first();
        $this->assertNotNull($abonnement->ends_at);
        $this->assertTrue($abonnement->ends_at->isFuture());

        $this->assertDatabaseHas('notifications', [
            'user_id' => $candidat->id,
            'titre'   => 'Abonnement activé',
        ]);

        Notification::assertSentTo($candidat, AbonnementActiveNotification::class);
    }

    public function test_store_rejette_un_plan_reserve_a_un_autre_role(): void
    {
        $admin     = $this->creerAdmin();
        $candidat  = $this->creerCandidat();
        $planRecru = $this->creerPlan(['target_type' => 'recruteur']);

        $this->actingAs($admin)
            ->post(route('admin.abonnements.store'), [
                'email'   => $candidat->email,
                'plan_id' => $planRecru->id,
            ])
            ->assertSessionHasErrors('plan_id');

        $this->assertDatabaseCount('abonnements', 0);
    }

    public function test_store_accepte_un_plan_both_pour_nimporte_quel_role(): void
    {
        $admin     = $this->creerAdmin();
        $recruteur = $this->creerRecruteur();
        $plan      = $this->creerPlan(['target_type' => 'both']);

        $this->actingAs($admin)
            ->post(route('admin.abonnements.store'), [
                'email'   => $recruteur->email,
                'plan_id' => $plan->id,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('abonnements', [
            'user_id' => $recruteur->id,
            'plan_id' => $plan->id,
            'status'  => 'active',
        ]);
    }

    public function test_store_email_inconnu_echoue_la_validation(): void
    {
        $admin = $this->creerAdmin();
        $plan  = $this->creerPlan(['target_type' => 'candidat']);

        $this->actingAs($admin)
            ->post(route('admin.abonnements.store'), [
                'email'   => 'inconnu@example.com',
                'plan_id' => $plan->id,
            ])
            ->assertSessionHasErrors('email');

        $this->assertDatabaseCount('abonnements', 0);
    }
}
