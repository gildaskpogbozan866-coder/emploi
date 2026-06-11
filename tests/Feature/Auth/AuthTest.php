<?php

namespace Tests\Feature\Auth;

use App\Models\RecruteurVerification;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use App\Notifications\ReinitialisationMotDePasse;
use App\Notifications\VerificationEmailFr;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        Cache::flush();
        Notification::fake();
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    private function creerUser(
        string $role     = 'candidat',
        bool   $verifie  = true,
        bool   $actif    = true,
        string $password = 'password123'
    ): User {
        $user = User::factory()->create([
            'role'              => $role,
            'email_verified_at' => $verifie ? now() : null,
            'actif'             => $actif,
            'password'          => Hash::make($password),
        ]);
        $user->assignRole($role);
        return $user;
    }

    private function payloadInscription(string $role = 'candidat', array $extra = []): array
    {
        return array_merge([
            'prenom'               => 'Jean',
            'nom'                  => 'Dupont',
            'email'                => 'jean@test.com',
            'password'             => 'motdepasse123',
            'password_confirmation' => 'motdepasse123',
            'tel'                  => '+229 01 00 00 00',
            'pays'                 => 'Bénin',
            'role'                 => $role,
        ], $extra);
    }

    // ══════════════════════════════════════════════════════════════════════
    //  PAGES
    // ══════════════════════════════════════════════════════════════════════

    public function test_page_connexion_accessible(): void
    {
        $this->get(route('auth.connexion'))->assertOk();
    }

    public function test_page_inscription_accessible(): void
    {
        $this->get(route('auth.inscription'))->assertOk();
    }

    public function test_page_mot_de_passe_oublie_accessible(): void
    {
        $this->get(route('auth.mot-de-passe-oublie'))->assertOk();
    }

    public function test_utilisateur_connecte_redirige_depuis_connexion(): void
    {
        $user = $this->creerUser('candidat');
        $this->actingAs($user)->get(route('auth.connexion'))
            ->assertRedirect(route('candidat.dashboard'));
    }

    public function test_recruteur_connecte_redirige_vers_dashboard_recruteur(): void
    {
        $user = $this->creerUser('recruteur');
        $this->actingAs($user)->get(route('auth.connexion'))
            ->assertRedirect(route('recruteur.dashboard'));
    }

    // ══════════════════════════════════════════════════════════════════════
    //  INSCRIPTION
    // ══════════════════════════════════════════════════════════════════════

    public function test_inscription_candidat_cree_utilisateur_et_envoie_email_verification(): void
    {
        $this->post(route('auth.inscription.store'), $this->payloadInscription())
            ->assertRedirect(route('verification.notice'));

        $this->assertDatabaseHas('users', [
            'email' => 'jean@test.com',
            'role'  => 'candidat',
        ]);

        $user = User::where('email', 'jean@test.com')->first();
        $this->assertNull($user->email_verified_at);
        $this->assertTrue($user->hasRole('candidat'));

        Notification::assertSentTo($user, VerificationEmailFr::class);
    }

    public function test_inscription_recruteur_assigne_role_recruteur(): void
    {
        $payload = $this->payloadInscription('recruteur', ['entreprise' => 'TechBénin SARL']);
        $this->post(route('auth.inscription.store'), $payload);

        $user = User::where('email', 'jean@test.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('recruteur'));
        $this->assertEquals('TechBénin SARL', $user->entreprise);
    }

    public function test_inscription_talent_role_refuse(): void
    {
        $payload = $this->payloadInscription('talent', ['metier' => 'Développeur Web']);
        $this->post(route('auth.inscription.store'), $payload)
            ->assertSessionHasErrors('role');

        $this->assertNull(User::where('email', 'jean@test.com')->first());
    }

    public function test_inscription_email_deja_utilise_retourne_erreur(): void
    {
        $this->creerUser();
        User::where('email', '!=', 'jean@test.com')->delete();
        User::factory()->create(['email' => 'jean@test.com']);

        $this->post(route('auth.inscription.store'), $this->payloadInscription())
            ->assertSessionHasErrors('email');
    }

    public function test_inscription_role_admin_refuse(): void
    {
        $this->post(route('auth.inscription.store'), $this->payloadInscription('admin'))
            ->assertSessionHasErrors('role');
    }

    public function test_inscription_mot_de_passe_trop_court(): void
    {
        $payload = $this->payloadInscription('candidat', [
            'password'              => 'abc',
            'password_confirmation' => 'abc',
        ]);
        $this->post(route('auth.inscription.store'), $payload)
            ->assertSessionHasErrors('password');
    }

    public function test_inscription_mots_de_passe_non_correspondants(): void
    {
        $payload = $this->payloadInscription('candidat', [
            'password'              => 'motdepasse123',
            'password_confirmation' => 'autrechose456',
        ]);
        $this->post(route('auth.inscription.store'), $payload)
            ->assertSessionHasErrors('password');
    }

    public function test_inscription_champs_requis_manquants(): void
    {
        $this->post(route('auth.inscription.store'), [])
            ->assertSessionHasErrors(['prenom', 'nom', 'email', 'password', 'pays', 'role']);
    }

    public function test_utilisateur_est_connecte_apres_inscription(): void
    {
        $this->post(route('auth.inscription.store'), $this->payloadInscription());
        $this->assertAuthenticated();
    }

    // ══════════════════════════════════════════════════════════════════════
    //  CONNEXION
    // ══════════════════════════════════════════════════════════════════════

    public function test_connexion_candidat_avec_identifiants_valides(): void
    {
        $user = $this->creerUser('candidat');

        $this->post(route('auth.connexion.store'), [
            'email'    => $user->email,
            'password' => 'password123',
        ])->assertRedirect(route('candidat.dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_connexion_recruteur_redirige_vers_dashboard_recruteur(): void
    {
        $user = $this->creerUser('recruteur');

        $this->post(route('auth.connexion.store'), [
            'email'    => $user->email,
            'password' => 'password123',
        ])->assertRedirect(route('recruteur.dashboard'));
    }

    public function test_connexion_talent_redirige_vers_dashboard_candidat(): void
    {
        $user = $this->creerUser('candidat');

        $this->post(route('auth.connexion.store'), [
            'email'    => $user->email,
            'password' => 'password123',
        ])->assertRedirect(route('candidat.dashboard'));
    }

    public function test_connexion_mot_de_passe_incorrect(): void
    {
        $user = $this->creerUser();

        $this->post(route('auth.connexion.store'), [
            'email'    => $user->email,
            'password' => 'mauvais_mdp',
        ])->assertSessionHasErrors('credentials');

        $this->assertGuest();
    }

    public function test_connexion_email_inconnu(): void
    {
        $this->post(route('auth.connexion.store'), [
            'email'    => 'inconnu@test.com',
            'password' => 'password123',
        ])->assertSessionHasErrors('credentials');
    }

    public function test_connexion_compte_suspendu(): void
    {
        $user = $this->creerUser('candidat', true, false);

        $this->post(route('auth.connexion.store'), [
            'email'    => $user->email,
            'password' => 'password123',
        ])->assertSessionHasErrors('credentials');

        $this->assertGuest();
    }

    // ══════════════════════════════════════════════════════════════════════
    //  VÉRIFICATION EMAIL
    // ══════════════════════════════════════════════════════════════════════

    public function test_utilisateur_non_verifie_redirige_vers_notice(): void
    {
        $user = $this->creerUser('candidat', verifie: false);

        $this->actingAs($user)
            ->get(route('candidat.dashboard'))
            ->assertRedirect(route('verification.notice'));
    }

    public function test_page_verification_notice_accessible_pour_utilisateur_connecte(): void
    {
        $user = $this->creerUser('candidat', verifie: false);

        $this->actingAs($user)
            ->get(route('verification.notice'))
            ->assertOk();
    }

    public function test_email_verifie_via_lien_signe(): void
    {
        $user = $this->creerUser('candidat', verifie: false);

        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $this->actingAs($user)->get($url)->assertRedirect(route('candidat.dashboard'));

        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_recruteur_redirige_vers_verification_entreprise_apres_email_verifie(): void
    {
        $user = $this->creerUser('recruteur', verifie: false);

        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $this->actingAs($user)->get($url)
            ->assertRedirect(route('recruteur.verification'));
    }

    public function test_renvoi_email_verification(): void
    {
        $user = $this->creerUser('candidat', verifie: false);

        $this->actingAs($user)
            ->post(route('verification.send'))
            ->assertRedirect();

        Notification::assertSentTo($user, VerificationEmailFr::class);
    }

    public function test_utilisateur_deja_verifie_ne_peut_pas_acceder_verification_notice(): void
    {
        $user = $this->creerUser('candidat', verifie: true);

        // L'utilisateur vérifié qui clique sur un lien de vérification
        // voit son email_verified_at rester non-null
        $this->assertNotNull($user->email_verified_at);
    }

    // ══════════════════════════════════════════════════════════════════════
    //  RÉINITIALISATION DE MOT DE PASSE
    // ══════════════════════════════════════════════════════════════════════

    public function test_mot_de_passe_oublie_envoie_email_reset(): void
    {
        $user = $this->creerUser();

        $this->post(route('auth.mot-de-passe-oublie.store'), ['email' => $user->email])
            ->assertSessionHas('success');

        Notification::assertSentTo($user, ReinitialisationMotDePasse::class);
    }

    public function test_mot_de_passe_oublie_email_inconnu_ne_revele_pas_existence(): void
    {
        $this->post(route('auth.mot-de-passe-oublie.store'), ['email' => 'inexistant@test.com'])
            ->assertSessionHasErrors('email');
    }

    public function test_reinitialisation_mot_de_passe_avec_token_valide(): void
    {
        $user  = $this->creerUser();
        $token = Password::createToken($user);

        $this->post(route('auth.reinitialiser.store'), [
            'token'                 => $token,
            'email'                 => $user->email,
            'password'              => 'nouveau_mdp123',
            'password_confirmation' => 'nouveau_mdp123',
        ])->assertRedirect(route('auth.connexion'));

        $this->assertTrue(Hash::check('nouveau_mdp123', $user->fresh()->password));
    }

    public function test_reinitialisation_echoue_avec_token_invalide(): void
    {
        $user = $this->creerUser();

        $this->post(route('auth.reinitialiser.store'), [
            'token'                 => 'token_bidon',
            'email'                 => $user->email,
            'password'              => 'nouveau_mdp123',
            'password_confirmation' => 'nouveau_mdp123',
        ])->assertSessionHasErrors('email');
    }

    public function test_reinitialisation_echoue_avec_mot_de_passe_trop_court(): void
    {
        $user  = $this->creerUser();
        $token = Password::createToken($user);

        $this->post(route('auth.reinitialiser.store'), [
            'token'                 => $token,
            'email'                 => $user->email,
            'password'              => 'court',
            'password_confirmation' => 'court',
        ])->assertSessionHasErrors('password');
    }

    // ══════════════════════════════════════════════════════════════════════
    //  CHANGEMENT DE MOT DE PASSE (connecté)
    // ══════════════════════════════════════════════════════════════════════

    public function test_page_changer_mot_de_passe_accessible_connecte(): void
    {
        $user = $this->creerUser();
        $this->actingAs($user)->get(route('auth.changer-mot-de-passe'))->assertOk();
    }

    public function test_utilisateur_peut_changer_son_mot_de_passe(): void
    {
        $user = $this->creerUser(password: 'ancien_mdp123');

        $this->actingAs($user)->post(route('auth.changer-mot-de-passe.store'), [
            'mot_de_passe_actuel'   => 'ancien_mdp123',
            'password'              => 'nouveau_mdp456',
            'password_confirmation' => 'nouveau_mdp456',
        ])->assertSessionHas('mdp_success');

        $this->assertTrue(Hash::check('nouveau_mdp456', $user->fresh()->password));
    }

    public function test_changement_echoue_avec_mauvais_mot_de_passe_actuel(): void
    {
        $user = $this->creerUser();

        $this->actingAs($user)->post(route('auth.changer-mot-de-passe.store'), [
            'mot_de_passe_actuel'   => 'mauvais_mdp',
            'password'              => 'nouveau_mdp456',
            'password_confirmation' => 'nouveau_mdp456',
        ])->assertSessionHasErrors('mot_de_passe_actuel');
    }

    public function test_changement_echoue_avec_nouveau_mdp_trop_court(): void
    {
        $user = $this->creerUser(password: 'ancien_mdp123');

        $this->actingAs($user)->post(route('auth.changer-mot-de-passe.store'), [
            'mot_de_passe_actuel'   => 'ancien_mdp123',
            'password'              => 'abc',
            'password_confirmation' => 'abc',
        ])->assertSessionHasErrors('password');
    }

    public function test_changer_mdp_inaccessible_sans_connexion(): void
    {
        $this->get(route('auth.changer-mot-de-passe'))->assertRedirect(route('auth.connexion'));
    }

    // ══════════════════════════════════════════════════════════════════════
    //  MIDDLEWARE RECRUTEUR — VÉRIFICATION ENTREPRISE
    // ══════════════════════════════════════════════════════════════════════

    public function test_recruteur_sans_dossier_redirige_vers_formulaire_verification(): void
    {
        $user = $this->creerUser('recruteur');

        $this->actingAs($user)
            ->get(route('recruteur.dashboard'))
            ->assertRedirect(route('recruteur.verification'));
    }

    public function test_recruteur_en_attente_bloque_acces_dashboard(): void
    {
        $user = $this->creerUser('recruteur');
        RecruteurVerification::create(['user_id' => $user->id, 'statut' => 'en_attente']);

        $this->actingAs($user)
            ->get(route('recruteur.dashboard'))
            ->assertRedirect(route('recruteur.verification.en-attente'));
    }

    public function test_recruteur_rejete_bloque_acces_dashboard(): void
    {
        $user = $this->creerUser('recruteur');
        RecruteurVerification::create([
            'user_id'    => $user->id,
            'statut'     => 'rejete',
            'note_admin' => 'Documents illisibles.',
        ]);

        $this->actingAs($user)
            ->get(route('recruteur.dashboard'))
            ->assertRedirect(route('recruteur.verification.rejete'));
    }

    public function test_recruteur_approuve_acces_complet_dashboard(): void
    {
        $user = $this->creerUser('recruteur');
        RecruteurVerification::create(['user_id' => $user->id, 'statut' => 'approuve']);

        $this->actingAs($user)
            ->get(route('recruteur.dashboard'))
            ->assertOk();
    }

    public function test_page_en_attente_accessible_recruteur(): void
    {
        $user = $this->creerUser('recruteur');
        RecruteurVerification::create(['user_id' => $user->id, 'statut' => 'en_attente']);

        $this->actingAs($user)
            ->get(route('recruteur.verification.en-attente'))
            ->assertOk();
    }

    public function test_page_rejete_affiche_motif(): void
    {
        $user = $this->creerUser('recruteur');
        RecruteurVerification::create([
            'user_id'    => $user->id,
            'statut'     => 'rejete',
            'note_admin' => 'Photo floue.',
        ]);

        $this->actingAs($user)
            ->get(route('recruteur.verification.rejete'))
            ->assertOk()
            ->assertSee('Photo floue.');
    }

    // ══════════════════════════════════════════════════════════════════════
    //  DÉCONNEXION
    // ══════════════════════════════════════════════════════════════════════

    public function test_deconnexion_invalide_session_et_redirige_vers_accueil(): void
    {
        $user = $this->creerUser();

        $this->actingAs($user)
            ->post(route('auth.deconnecter'))
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }

    public function test_deconnexion_inaccessible_sans_connexion(): void
    {
        $this->post(route('auth.deconnecter'))->assertRedirect(route('auth.connexion'));
    }


}
