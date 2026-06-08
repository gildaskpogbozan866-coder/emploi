<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        Cache::flush(); // isoler les compteurs de rate-limiting entre chaque test
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    private function creerUser(
        string $email = 'test@test.com',
        string $role  = 'candidat',
        bool   $actif = true
    ): User {
        $user = User::create([
            'prenom'            => 'Test',
            'nom'               => 'User',
            'email'             => $email,
            'pays'              => 'Bénin',
            'role'              => $role,
            'actif'             => $actif,
            'email_verified_at' => now(),
        ]);
        $user->assignRole($role);
        return $user;
    }

    private function creerOtp(string $email, string $type, ?array $payload = null): string
    {
        $code = '123456';
        DB::table('otp_codes')->where('email', $email)->delete();
        DB::table('otp_codes')->insert([
            'email'      => $email,
            'code'       => $code,
            'type'       => $type,
            'payload'    => $payload ? json_encode($payload) : null,
            'expires_at' => Carbon::now()->addMinutes(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return $code;
    }

    private function payloadInscription(string $role = 'candidat', array $extra = []): array
    {
        return array_merge([
            'prenom' => 'Jean',
            'nom'    => 'Dupont',
            'email'  => 'jean@test.com',
            'tel'    => '+229 01 00 00 00',
            'pays'   => 'Bénin',
            'role'   => $role,
        ], $extra);
    }

    // ══════════════════════════════════════════════════════════════════════
    //  PAGES
    // ══════════════════════════════════════════════════════════════════════

    public function test_page_connexion_accessible(): void
    {
        $this->get(route('auth.connexion'))->assertStatus(200);
    }

    public function test_page_inscription_accessible(): void
    {
        $this->get(route('auth.inscription'))->assertStatus(200);
    }

    public function test_page_verification_accessible(): void
    {
        $this->get(route('auth.verification-email'))->assertStatus(200);
    }

    public function test_utilisateur_connecte_redirige_depuis_connexion_vers_son_dashboard(): void
    {
        $user = $this->creerUser('test@test.com', 'candidat');

        $this->actingAs($user)
            ->get(route('auth.connexion'))
            ->assertRedirect(route('candidat.dashboard'));
    }

    public function test_recruteur_connecte_redirige_vers_dashboard_recruteur(): void
    {
        $user = $this->creerUser('rec@test.com', 'recruteur');

        $this->actingAs($user)
            ->get(route('auth.inscription'))
            ->assertRedirect(route('recruteur.dashboard'));
    }

    // ══════════════════════════════════════════════════════════════════════
    //  INSCRIPTION
    // ══════════════════════════════════════════════════════════════════════

    public function test_inscription_candidat_cree_otp_et_redirige_vers_verification(): void
    {
        $this->post(route('auth.inscription.store'), $this->payloadInscription())
            ->assertRedirect(route('auth.verification-email'));

        $this->assertDatabaseHas('otp_codes', [
            'email' => 'jean@test.com',
            'type'  => 'register',
        ]);
    }

    public function test_inscription_talent_avec_metier_cree_otp(): void
    {
        $payload = $this->payloadInscription('talent', ['metier' => 'Développeur Web']);

        $this->post(route('auth.inscription.store'), $payload)
            ->assertRedirect(route('auth.verification-email'));

        $this->assertDatabaseHas('otp_codes', [
            'email' => 'jean@test.com',
            'type'  => 'register',
        ]);
    }

    public function test_inscription_recruteur_avec_entreprise_cree_otp(): void
    {
        $payload = $this->payloadInscription('recruteur', ['entreprise' => 'TechBénin SARL']);

        $this->post(route('auth.inscription.store'), $payload)
            ->assertRedirect(route('auth.verification-email'));

        $this->assertDatabaseHas('otp_codes', [
            'email' => 'jean@test.com',
            'type'  => 'register',
        ]);
    }

    public function test_inscription_email_deja_utilise_retourne_erreur(): void
    {
        $this->creerUser('jean@test.com');

        $this->post(route('auth.inscription.store'), $this->payloadInscription())
            ->assertSessionHasErrors('email');
    }

    public function test_inscription_role_admin_est_refuse(): void
    {
        $payload         = $this->payloadInscription();
        $payload['role'] = 'admin';

        $this->post(route('auth.inscription.store'), $payload)
            ->assertSessionHasErrors('role');
    }

    public function test_inscription_champs_requis_manquants_retourne_erreurs(): void
    {
        $this->post(route('auth.inscription.store'), [])
            ->assertSessionHasErrors(['prenom', 'nom', 'email', 'pays', 'role']);
    }

    // ══════════════════════════════════════════════════════════════════════
    //  CONNEXION (envoi OTP)
    // ══════════════════════════════════════════════════════════════════════

    public function test_connexion_email_inconnu_retourne_erreur(): void
    {
        $this->post(route('auth.connexion.otp'), ['email' => 'inconnu@test.com'])
            ->assertSessionHasErrors('email');
    }

    public function test_connexion_compte_suspendu_retourne_erreur(): void
    {
        $this->creerUser('suspendu@test.com', 'candidat', actif: false);

        $this->post(route('auth.connexion.otp'), ['email' => 'suspendu@test.com'])
            ->assertSessionHasErrors('email');

        $this->assertDatabaseMissing('otp_codes', ['email' => 'suspendu@test.com']);
    }

    public function test_connexion_email_valide_cree_otp_login_et_redirige(): void
    {
        $this->creerUser('actif@test.com');

        $this->post(route('auth.connexion.otp'), ['email' => 'actif@test.com'])
            ->assertRedirect(route('auth.verification-email'));

        $this->assertDatabaseHas('otp_codes', [
            'email' => 'actif@test.com',
            'type'  => 'login',
        ]);
    }

    // ══════════════════════════════════════════════════════════════════════
    //  VÉRIFICATION OTP
    // ══════════════════════════════════════════════════════════════════════

    public function test_verification_register_candidat_cree_user_avec_role_spatie(): void
    {
        $email   = 'nouveau@test.com';
        $payload = $this->payloadInscription('candidat', ['email' => $email]);
        $code    = $this->creerOtp($email, 'register', $payload);

        $this->withSession(['otp_email' => $email])
            ->post(route('auth.verification.otp'), ['code' => $code]);

        $user = User::where('email', $email)->first();
        $this->assertNotNull($user);
        $this->assertEquals('candidat', $user->role);
        $this->assertTrue($user->hasRole('candidat'));
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_verification_register_redirige_vers_compte_confirme(): void
    {
        $email   = 'nouveau@test.com';
        $payload = $this->payloadInscription('candidat', ['email' => $email]);
        $code    = $this->creerOtp($email, 'register', $payload);

        $this->withSession(['otp_email' => $email])
            ->post(route('auth.verification.otp'), ['code' => $code])
            ->assertRedirect(route('auth.compte-confirme'));
    }

    public function test_verification_register_talent_assigne_role_talent(): void
    {
        $email   = 'talent@test.com';
        $payload = $this->payloadInscription('talent', ['email' => $email, 'metier' => 'Designer']);
        $code    = $this->creerOtp($email, 'register', $payload);

        $this->withSession(['otp_email' => $email])
            ->post(route('auth.verification.otp'), ['code' => $code]);

        $this->assertTrue(User::where('email', $email)->first()->hasRole('talent'));
    }

    public function test_verification_register_recruteur_persiste_entreprise(): void
    {
        $email   = 'rec@test.com';
        $payload = $this->payloadInscription('recruteur', [
            'email'      => $email,
            'entreprise' => 'TechBénin SARL',
        ]);
        $code = $this->creerOtp($email, 'register', $payload);

        $this->withSession(['otp_email' => $email])
            ->post(route('auth.verification.otp'), ['code' => $code]);

        $this->assertDatabaseHas('users', [
            'email'      => $email,
            'entreprise' => 'TechBénin SARL',
            'role'       => 'recruteur',
        ]);
    }

    public function test_verification_login_candidat_redirige_vers_dashboard_candidat(): void
    {
        $this->creerUser('actif@test.com', 'candidat');
        $code = $this->creerOtp('actif@test.com', 'login');

        $this->withSession(['otp_email' => 'actif@test.com'])
            ->post(route('auth.verification.otp'), ['code' => $code])
            ->assertRedirect(route('candidat.dashboard'));
    }

    public function test_verification_login_recruteur_redirige_vers_dashboard_recruteur(): void
    {
        $this->creerUser('rec@test.com', 'recruteur');
        $code = $this->creerOtp('rec@test.com', 'login');

        $this->withSession(['otp_email' => 'rec@test.com'])
            ->post(route('auth.verification.otp'), ['code' => $code])
            ->assertRedirect(route('recruteur.dashboard'));
    }

    public function test_verification_login_talent_redirige_vers_dashboard_talent(): void
    {
        $this->creerUser('talent@test.com', 'talent');
        $code = $this->creerOtp('talent@test.com', 'login');

        $this->withSession(['otp_email' => 'talent@test.com'])
            ->post(route('auth.verification.otp'), ['code' => $code])
            ->assertRedirect(route('talent.dashboard'));
    }

    public function test_verification_code_invalide_retourne_erreur(): void
    {
        $email = 'actif@test.com';
        $this->creerUser($email);
        $this->creerOtp($email, 'login');

        $this->withSession(['otp_email' => $email])
            ->post(route('auth.verification.otp'), ['code' => '000000'])
            ->assertSessionHasErrors('code');
    }

    public function test_verification_code_expire_retourne_erreur(): void
    {
        $email = 'actif@test.com';
        $this->creerUser($email);

        DB::table('otp_codes')->insert([
            'email'      => $email,
            'code'       => '999999',
            'type'       => 'login',
            'payload'    => null,
            'expires_at' => Carbon::now()->subMinutes(1),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->withSession(['otp_email' => $email])
            ->post(route('auth.verification.otp'), ['code' => '999999'])
            ->assertSessionHasErrors('code');
    }

    public function test_verification_sans_session_redirige_vers_connexion(): void
    {
        $this->post(route('auth.verification.otp'), ['code' => '123456'])
            ->assertRedirect(route('auth.connexion'));
    }

    public function test_otp_supprime_de_la_base_apres_verification_reussie(): void
    {
        $email   = 'nouveau@test.com';
        $payload = $this->payloadInscription('candidat', ['email' => $email]);
        $code    = $this->creerOtp($email, 'register', $payload);

        $this->withSession(['otp_email' => $email])
            ->post(route('auth.verification.otp'), ['code' => $code]);

        $this->assertDatabaseMissing('otp_codes', ['email' => $email]);
    }

    // ══════════════════════════════════════════════════════════════════════
    //  RENVOI OTP
    // ══════════════════════════════════════════════════════════════════════

    public function test_renvoyer_otp_genere_un_nouveau_code(): void
    {
        $email      = 'actif@test.com';
        $ancienCode = $this->creerOtp($email, 'login');

        $this->withSession(['otp_email' => $email])
            ->post(route('auth.verification.renvoyer'));

        $nouveau = DB::table('otp_codes')->where('email', $email)->first();
        $this->assertNotNull($nouveau);
        $this->assertNotEquals($ancienCode, $nouveau->code);
    }

    public function test_renvoyer_otp_sans_session_redirige_vers_connexion(): void
    {
        $this->post(route('auth.verification.renvoyer'))
            ->assertRedirect(route('auth.connexion'));
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

    // ══════════════════════════════════════════════════════════════════════
    //  RATE LIMITING
    // ══════════════════════════════════════════════════════════════════════

    public function test_rate_limit_inscription_bloque_a_partir_du_6eme_envoi(): void
    {
        // Même email + même IP (127.0.0.1 en test) = même clé de throttle.
        // L'email n'existe pas encore en users, la validation passe les 5 fois.
        $payload = $this->payloadInscription('candidat', ['email' => 'spam@test.com']);

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('auth.inscription.store'), $payload);
        }

        $this->post(route('auth.inscription.store'), $payload)
            ->assertSessionHasErrors('email');
    }

    public function test_rate_limit_connexion_bloque_a_partir_du_6eme_envoi(): void
    {
        $this->creerUser('actif@test.com');

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('auth.connexion.otp'), ['email' => 'actif@test.com']);
        }

        $this->post(route('auth.connexion.otp'), ['email' => 'actif@test.com'])
            ->assertSessionHasErrors('email');
    }
}
