<?php

namespace Tests\Feature\Public;

use App\Models\Candidature;
use App\Models\CV;
use App\Models\Notification;
use App\Models\Offre;
use App\Models\Plan;
use App\Models\RecruteurVerification;
use App\Models\User;
use App\Notifications\CandidatureRecueNotification;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CandidatureNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        Cache::flush();
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

    // ── Guard : recruteur ne peut pas postuler ────────────

    public function test_postuler_bloque_un_recruteur(): void
    {
        $recruteur = $this->creerRecruteur();
        $offre     = $this->creerOffre($recruteur);

        $this->actingAs($recruteur)
            ->get(route('offre.postuler', $offre))
            ->assertRedirect(route('recruteur.dashboard'))
            ->assertSessionHas('error');
    }

    public function test_storerCandidature_bloque_un_recruteur(): void
    {
        NotificationFacade::fake();

        $recruteur = $this->creerRecruteur();
        $offre     = $this->creerOffre($recruteur);

        $this->actingAs($recruteur)
            ->post(route('offre.postuler.store', $offre), [
                'message_motivation' => 'Je veux ce poste',
            ])
            ->assertRedirect(route('home'))
            ->assertSessionHas('error');

        $this->assertDatabaseMissing('candidatures', ['candidat_id' => $recruteur->id]);
    }

    // ── Soumission candidature : notifications ────────────

    public function test_soumission_envoie_email_de_confirmation_au_candidat(): void
    {
        NotificationFacade::fake();

        $recruteur = $this->creerRecruteur();
        $candidat  = $this->creerCandidat();
        $offre     = $this->creerOffre($recruteur);

        $this->actingAs($candidat)
            ->post(route('offre.postuler.store', $offre), [
                'message_motivation' => 'Motivé !',
            ]);

        NotificationFacade::assertSentTo(
            $candidat,
            CandidatureRecueNotification::class,
            fn ($n) => $n->offre->id === $offre->id
        );
    }

    public function test_soumission_cree_notification_inapp_pour_le_recruteur(): void
    {
        NotificationFacade::fake();

        $recruteur = $this->creerRecruteur();
        $candidat  = $this->creerCandidat();
        $offre     = $this->creerOffre($recruteur);

        $this->actingAs($candidat)
            ->post(route('offre.postuler.store', $offre), [
                'message_motivation' => 'Candidature test',
            ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $recruteur->id,
            'type'    => 'candidature',
        ]);
    }

    public function test_soumission_en_double_bloquee(): void
    {
        NotificationFacade::fake();

        $recruteur = $this->creerRecruteur();
        $candidat  = $this->creerCandidat();
        $offre     = $this->creerOffre($recruteur);

        Candidature::create([
            'offre_id'    => $offre->id,
            'candidat_id' => $candidat->id,
            'statut'      => 'envoyee',
        ]);

        $this->actingAs($candidat)
            ->post(route('offre.postuler.store', $offre))
            ->assertRedirect()
            ->assertSessionHas('error_duplicate');

        // Pas de deuxième email envoyé
        NotificationFacade::assertNothingSent();
    }

    public function test_soumission_redirige_vers_succes(): void
    {
        NotificationFacade::fake();

        $recruteur = $this->creerRecruteur();
        $candidat  = $this->creerCandidat();
        $offre     = $this->creerOffre($recruteur);

        $this->actingAs($candidat)
            ->post(route('offre.postuler.store', $offre), [
                'message_motivation' => 'Motivé !',
            ])
            ->assertRedirect(route('offre.candidature-succes', $offre));
    }

    // ── Validation ───────────────────────────────────────

    public function test_storerCandidature_redirige_si_non_connecte(): void
    {
        $recruteur = $this->creerRecruteur();
        $offre     = $this->creerOffre($recruteur);

        $this->post(route('offre.postuler.store', $offre))
            ->assertRedirect(route('auth.connexion'));
    }

    public function test_storerCandidature_rejette_fichier_invalide(): void
    {
        Storage::fake('public');
        NotificationFacade::fake();

        $recruteur = $this->creerRecruteur();
        $candidat  = $this->creerCandidat();
        $offre     = $this->creerOffre($recruteur);

        $this->actingAs($candidat)
            ->post(route('offre.postuler.store', $offre), [
                'cv_file' => UploadedFile::fake()->create('malware.exe', 100),
            ])
            ->assertSessionHasErrors('cv_file');

        $this->assertDatabaseMissing('candidatures', ['candidat_id' => $candidat->id]);
    }

    public function test_storerCandidature_accepte_cv_fichier_pdf(): void
    {
        Storage::fake('public');
        NotificationFacade::fake();

        $recruteur = $this->creerRecruteur();
        $candidat  = $this->creerCandidat();
        $offre     = $this->creerOffre($recruteur);

        $this->actingAs($candidat)
            ->post(route('offre.postuler.store', $offre), [
                'cv_file' => UploadedFile::fake()->create('cv.pdf', 200, 'application/pdf'),
            ])
            ->assertRedirect(route('offre.candidature-succes', $offre));

        $candidature = Candidature::where('candidat_id', $candidat->id)->first();
        $this->assertNotNull($candidature);
        $this->assertNotNull($candidature->cv_path);
        Storage::disk('public')->assertExists($candidature->cv_path);
    }

    public function test_storerCandidature_ignore_cv_id_appartenant_a_un_autre(): void
    {
        NotificationFacade::fake();

        $recruteur  = $this->creerRecruteur();
        $candidat   = $this->creerCandidat();
        $autre      = $this->creerCandidat();
        $offre      = $this->creerOffre($recruteur);
        $cvAutre    = CV::create(['candidat_id' => $autre->id, 'titre_poste' => 'Dev', 'pays' => 'BJ']);

        $this->actingAs($candidat)
            ->post(route('offre.postuler.store', $offre), [
                'message_motivation' => 'Test',
                'cv_id'              => $cvAutre->id,
            ]);

        $candidature = Candidature::where('candidat_id', $candidat->id)->first();
        $this->assertNotNull($candidature);
        $this->assertNull($candidature->cv_id, 'Un cv_id étranger ne doit pas être accepté');
    }

    public function test_storerCandidature_utilise_cv_profil_si_appartient_au_candidat(): void
    {
        NotificationFacade::fake();

        $recruteur = $this->creerRecruteur();
        $candidat  = $this->creerCandidat();
        $offre     = $this->creerOffre($recruteur);
        $cv        = CV::create(['candidat_id' => $candidat->id, 'titre_poste' => 'Dev', 'pays' => 'BJ']);

        $this->actingAs($candidat)
            ->post(route('offre.postuler.store', $offre), [
                'message_motivation' => 'Test',
                'cv_id'              => $cv->id,
            ]);

        $candidature = Candidature::where('candidat_id', $candidat->id)->first();
        $this->assertEquals($cv->id, $candidature->cv_id);
    }

    // ── Recruteur : bell mark-as-read ────────────────────

    public function test_recruteur_peut_marquer_ses_notifications_comme_lues(): void
    {
        $recruteur = $this->creerRecruteur();
        Notification::create([
            'user_id' => $recruteur->id,
            'type'    => 'candidature',
            'titre'   => 'Nouvelle candidature',
            'contenu' => 'Test',
            'lu'      => false,
        ]);

        // Doit être dans le groupe recruteur.approuve pour accéder à l'espace recruteur
        $this->actingAs($recruteur)
            ->post(route('recruteur.notifications.lues'))
            ->assertRedirect();

        $this->assertEquals(0, Notification::where('user_id', $recruteur->id)->where('lu', false)->count());
    }
}
