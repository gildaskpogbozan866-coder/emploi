<?php

namespace Tests\Feature;

use App\Models\Alerte;
use App\Models\Notification;
use App\Models\Offre;
use App\Models\RecruteurVerification;
use App\Models\User;
use App\Notifications\AlerteOffreNotification;
use App\Services\AlerteService;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Tests\TestCase;

class AlerteServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        Cache::flush();
        \App\Models\TypeContrat::insert([
            ['code' => 'CDI',   'libelle' => 'Contrat à Durée Indéterminée', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'Stage', 'libelle' => 'Stage',                        'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    // ── Helpers ───────────────────────────────────────────

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
        RecruteurVerification::create(['user_id' => $user->id, 'statut' => 'approuve']);
        return $user;
    }

    private function creerOffre(User $recruteur, array $attrs = []): Offre
    {
        return Offre::factory()->create(array_merge([
            'recruteur_id' => $recruteur->id,
            'statut'       => 'active',
        ], $attrs));
    }

    private function creerAlerte(User $candidat, array $attrs = []): Alerte
    {
        return Alerte::create(array_merge([
            'user_id'    => $candidat->id,
            'nom'        => 'Mon alerte test',
            'frequence'  => 'immediat',
            'active'     => true,
        ], $attrs));
    }

    // ── matcheOffre ───────────────────────────────────────

    public function test_matcheoffre_retourne_true_si_metier_dans_titre(): void
    {
        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $alerte    = $this->creerAlerte($candidat, ['metier' => 'Développeur']);
        $offre     = $this->creerOffre($recruteur, ['titre' => 'Développeur Laravel Senior']);

        $service = app(AlerteService::class);
        $this->assertTrue($service->matcheOffre($alerte, $offre));
    }

    public function test_matcheoffre_retourne_false_si_metier_absent(): void
    {
        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $alerte    = $this->creerAlerte($candidat, ['metier' => 'Comptable']);
        $metier    = \App\Models\Metier::create(['nom' => 'Développeur Backend', 'slug' => 'developpeur-backend']);
        $offre     = $this->creerOffre($recruteur, ['titre' => 'Développeur Laravel', 'metier_id' => $metier->id]);

        $service = app(AlerteService::class);
        $this->assertFalse($service->matcheOffre($alerte, $offre));
    }

    public function test_matcheoffre_filtre_par_localisation(): void
    {
        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $alerte    = $this->creerAlerte($candidat, ['localisation' => 'Cotonou']);
        $offre     = $this->creerOffre($recruteur, ['localisation' => 'Parakou, Bénin']);

        $service = app(AlerteService::class);
        $this->assertFalse($service->matcheOffre($alerte, $offre));
    }

    public function test_matcheoffre_localisation_correspondante(): void
    {
        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $alerte    = $this->creerAlerte($candidat, ['localisation' => 'Cotonou']);
        $offre     = $this->creerOffre($recruteur, ['localisation' => 'Cotonou, Bénin']);

        $service = app(AlerteService::class);
        $this->assertTrue($service->matcheOffre($alerte, $offre));
    }

    public function test_matcheoffre_filtre_par_type_contrat(): void
    {
        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $alerte    = $this->creerAlerte($candidat, ['type_contrat' => 'CDI']);
        $offre     = $this->creerOffre($recruteur, [
            'type_contrat_id' => \App\Models\TypeContrat::where('code', 'Stage')->value('id'),
        ]);

        $service = app(AlerteService::class);
        $this->assertFalse($service->matcheOffre($alerte, $offre));
    }

    public function test_matcheoffre_sans_criteres_matche_tout(): void
    {
        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $alerte    = $this->creerAlerte($candidat);
        $offre     = $this->creerOffre($recruteur);

        $service = app(AlerteService::class);
        $this->assertTrue($service->matcheOffre($alerte, $offre));
    }

    // ── notifierImmediat ──────────────────────────────────

    public function test_notifier_immediat_cree_notification_inapp(): void
    {
        NotificationFacade::fake();

        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $this->creerAlerte($candidat, ['metier' => 'Développeur']);
        $offre = $this->creerOffre($recruteur, [
            'titre'       => 'Développeur Laravel',
            'description' => 'Travail en Laravel avancé',
        ]);

        app(AlerteService::class)->notifierImmediat($offre);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $candidat->id,
            'type'    => 'alerte',
        ]);
    }

    public function test_notifier_immediat_ne_leak_pas_le_json_du_type_contrat_dans_le_contenu(): void
    {
        NotificationFacade::fake();

        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $this->creerAlerte($candidat, ['metier' => 'Développeur']);
        $offre = $this->creerOffre($recruteur, [
            'titre'           => 'Développeur Laravel',
            'type_contrat_id' => \App\Models\TypeContrat::where('code', 'CDI')->value('id'),
        ]);

        app(AlerteService::class)->notifierImmediat($offre);

        $notification = Notification::where('user_id', $candidat->id)->where('type', 'alerte')->first();
        $this->assertNotNull($notification);
        $this->assertStringNotContainsString('{"id"', $notification->contenu);
        $this->assertStringContainsString('Contrat à Durée Indéterminée', $notification->contenu);
    }

    public function test_notifier_immediat_envoie_email_au_candidat(): void
    {
        NotificationFacade::fake();

        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $this->creerAlerte($candidat, ['metier' => 'Développeur']);
        $offre = $this->creerOffre($recruteur, [
            'titre'       => 'Développeur Laravel',
            'description' => 'Maîtrise Laravel requise',
        ]);

        app(AlerteService::class)->notifierImmediat($offre);

        NotificationFacade::assertSentTo(
            $candidat,
            AlerteOffreNotification::class,
            fn ($n) => $n->offre->id === $offre->id
        );
    }

    public function test_notifier_immediat_ne_notifie_pas_si_alerte_inactive(): void
    {
        NotificationFacade::fake();

        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $this->creerAlerte($candidat, ['metier' => 'Développeur', 'active' => false]);
        $offre = $this->creerOffre($recruteur, ['titre' => 'Développeur Laravel']);

        app(AlerteService::class)->notifierImmediat($offre);

        $this->assertDatabaseMissing('notifications', ['user_id' => $candidat->id, 'type' => 'alerte']);
        NotificationFacade::assertNothingSent();
    }

    public function test_notifier_immediat_evite_les_doublons(): void
    {
        NotificationFacade::fake();

        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $this->creerAlerte($candidat);
        $offre = $this->creerOffre($recruteur, ['titre' => 'Développeur']);

        $service = app(AlerteService::class);
        $service->notifierImmediat($offre);
        $service->notifierImmediat($offre); // Deuxième appel

        $this->assertEquals(1, Notification::where('user_id', $candidat->id)->where('type', 'alerte')->count());
    }

    public function test_notifier_immediat_ne_notifie_pas_frequence_quotidienne(): void
    {
        NotificationFacade::fake();

        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $this->creerAlerte($candidat, ['frequence' => 'quotidien']);
        $offre = $this->creerOffre($recruteur);

        app(AlerteService::class)->notifierImmediat($offre);

        $this->assertDatabaseMissing('notifications', ['user_id' => $candidat->id, 'type' => 'alerte']);
    }

    // ── Admin activation offre ────────────────────────────

    public function test_admin_activation_offre_declenche_alertes(): void
    {
        NotificationFacade::fake();

        $adminUser = User::factory()->create(['role' => 'admin']);
        $adminUser->assignRole('admin');

        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $this->creerAlerte($candidat, ['metier' => 'Comptable']);
        $offre = $this->creerOffre($recruteur, [
            'titre'       => 'Comptable senior',
            'description' => 'Poste de comptable',
            'statut'      => 'en_attente',
        ]);

        $this->actingAs($adminUser)
            ->patch(route('admin.offres.statut', $offre), ['statut' => 'active'])
            ->assertRedirect();

        $this->assertDatabaseHas('notifications', [
            'user_id' => $candidat->id,
            'type'    => 'alerte',
        ]);
    }

    public function test_admin_activation_renseigne_published_at(): void
    {
        NotificationFacade::fake();

        $adminUser = User::factory()->create(['role' => 'admin']);
        $adminUser->assignRole('admin');

        $recruteur = $this->creerRecruteur();
        $offre = $this->creerOffre($recruteur, ['statut' => 'en_attente']);
        $this->assertNull($offre->published_at);

        $this->actingAs($adminUser)
            ->patch(route('admin.offres.statut', $offre), ['statut' => 'active']);

        $this->assertNotNull($offre->fresh()->published_at);
    }

    /**
     * Bug réel corrigé : published_at n'était jamais renseigné à l'activation
     * d'une offre (ni via le recruteur, ni via l'admin), donc notifierDigest()
     * — utilisé par les alertes quotidiennes/hebdomadaires — ne trouvait jamais
     * aucune offre, même quand le planificateur tournait normalement.
     */
    public function test_notifierdigest_trouve_une_offre_activee_par_ladmin(): void
    {
        NotificationFacade::fake();

        $adminUser = User::factory()->create(['role' => 'admin']);
        $adminUser->assignRole('admin');

        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $this->creerAlerte($candidat, ['metier' => 'Comptable', 'frequence' => 'quotidien']);
        $offre = $this->creerOffre($recruteur, [
            'titre'  => 'Comptable senior',
            'statut' => 'en_attente',
        ]);

        $this->actingAs($adminUser)
            ->patch(route('admin.offres.statut', $offre), ['statut' => 'active']);

        $count = (new AlerteService())->notifierDigest('quotidien');

        $this->assertSame(1, $count);
    }

    public function test_admin_activation_ne_declenche_pas_alertes_si_deja_active(): void
    {
        NotificationFacade::fake();

        $adminUser = User::factory()->create(['role' => 'admin']);
        $adminUser->assignRole('admin');

        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $this->creerAlerte($candidat);
        $offre = $this->creerOffre($recruteur, ['statut' => 'active']);

        // Passer de active à active ne doit pas re-notifier
        $this->actingAs($adminUser)
            ->patch(route('admin.offres.statut', $offre), ['statut' => 'active']);

        $this->assertDatabaseMissing('notifications', ['user_id' => $candidat->id, 'type' => 'alerte']);
    }
}
