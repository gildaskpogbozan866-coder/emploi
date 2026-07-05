<?php

namespace Tests\Feature\Candidat;

use App\Models\Abonnement;
use App\Models\Alerte;
use App\Models\Plan;
use App\Models\PlanFeature;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class AlerteControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        Cache::flush();
    }

    private function creerCandidatPremium(int $alertLimit = 5): User
    {
        $user = User::factory()->candidat()->create();
        $user->assignRole('candidat');

        $plan = Plan::create([
            'name' => 'Premium', 'slug' => 'premium-'.uniqid(), 'target_type' => 'candidat',
            'price' => 5000, 'duration_days' => 30, 'is_free' => false, 'is_active' => true,
        ]);
        PlanFeature::create(['plan_id' => $plan->id, 'feature_key' => 'alert_limit', 'feature_value' => (string) $alertLimit]);
        Abonnement::create([
            'user_id' => $user->id, 'plan_id' => $plan->id,
            'starts_at' => now()->subDay(), 'ends_at' => now()->addMonth(), 'status' => 'active',
        ]);

        return $user;
    }

    private function criteresAlerte(): array
    {
        return [
            'metier'       => 'Comptable',
            'localisation' => 'Cotonou',
            'type_contrat' => 'CDI',
            'secteur'      => 'Banque',
            'frequence'    => 'immediat',
        ];
    }

    public function test_store_cree_une_alerte(): void
    {
        $candidat = $this->creerCandidatPremium();

        $this->actingAs($candidat)
            ->post(route('candidat.alertes.store'), $this->criteresAlerte())
            ->assertSessionHas('success');

        $this->assertSame(1, Alerte::where('user_id', $candidat->id)->count());
    }

    public function test_store_refuse_un_doublon_exact(): void
    {
        $candidat = $this->creerCandidatPremium();
        $this->actingAs($candidat)->post(route('candidat.alertes.store'), $this->criteresAlerte());

        $this->actingAs($candidat)
            ->post(route('candidat.alertes.store'), $this->criteresAlerte())
            ->assertSessionHas('error');

        $this->assertSame(1, Alerte::where('user_id', $candidat->id)->count());
    }

    public function test_store_refuse_un_doublon_meme_si_lancienne_est_inactive(): void
    {
        $candidat = $this->creerCandidatPremium();
        $this->actingAs($candidat)->post(route('candidat.alertes.store'), $this->criteresAlerte());
        Alerte::where('user_id', $candidat->id)->update(['active' => false]);

        $this->actingAs($candidat)
            ->post(route('candidat.alertes.store'), $this->criteresAlerte())
            ->assertSessionHas('error');

        $this->assertSame(1, Alerte::where('user_id', $candidat->id)->count());
    }

    public function test_store_refuse_un_doublon_meme_avec_une_frequence_differente(): void
    {
        $candidat = $this->creerCandidatPremium();
        $this->actingAs($candidat)->post(route('candidat.alertes.store'), $this->criteresAlerte());

        $this->actingAs($candidat)
            ->post(route('candidat.alertes.store'), array_merge($this->criteresAlerte(), ['frequence' => 'hebdomadaire']))
            ->assertSessionHas('error');

        $this->assertSame(1, Alerte::where('user_id', $candidat->id)->count());
    }

    public function test_store_autorise_des_criteres_differents(): void
    {
        $candidat = $this->creerCandidatPremium();
        $this->actingAs($candidat)->post(route('candidat.alertes.store'), $this->criteresAlerte());

        $this->actingAs($candidat)
            ->post(route('candidat.alertes.store'), array_merge($this->criteresAlerte(), ['metier' => 'Développeur']))
            ->assertSessionHas('success');

        $this->assertSame(2, Alerte::where('user_id', $candidat->id)->count());
    }
}
