<?php

namespace Tests\Feature\Candidat;

use App\Models\CandidatProfil;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfilControllerTest extends TestCase
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

    private function creerAutreRole(string $role): User
    {
        $user = User::factory()->create(['role' => $role]);
        $user->assignRole($role);
        return $user;
    }

    // ── Accès à la page ───────────────────────────────────

    public function test_page_profil_accessible_au_candidat_connecte(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->get(route('candidat.profil'))
            ->assertOk()
            ->assertViewIs('candidat.profil');
    }

    public function test_page_profil_redirige_si_non_connecte(): void
    {
        $this->get(route('candidat.profil'))
            ->assertRedirect(route('auth.connexion'));
    }

    public function test_page_profil_inaccessible_au_recruteur(): void
    {
        $recruteur = $this->creerAutreRole('recruteur');

        $this->actingAs($recruteur)
            ->get(route('candidat.profil'))
            ->assertForbidden();
    }

    public function test_page_profil_inaccessible_au_talent(): void
    {
        $talent = $this->creerAutreRole('talent');

        $this->actingAs($talent)
            ->get(route('candidat.profil'))
            ->assertForbidden();
    }

    // ── Mise à jour des infos personnelles ────────────────

    public function test_mise_a_jour_infos_personnelles(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->put(route('candidat.profil.update'), [
                'prenom' => 'Kofi',
                'nom'    => 'Mensah',
                'tel'    => '+229 97 00 00 01',
                'pays'   => 'Bénin',
            ])
            ->assertRedirect(route('candidat.profil'));

        $this->assertDatabaseHas('users', [
            'id'     => $candidat->id,
            'prenom' => 'Kofi',
            'nom'    => 'Mensah',
        ]);
    }

    public function test_mise_a_jour_cree_profil_candidat_si_inexistant(): void
    {
        $candidat = $this->creerCandidat();

        $this->assertNull($candidat->candidatProfil);

        $this->actingAs($candidat)
            ->put(route('candidat.profil.update'), [
                'prenom'              => $candidat->prenom,
                'nom'                 => $candidat->nom,
                'titre_professionnel' => 'Développeur Full Stack',
                'bio'                 => 'Passionné par le code.',
                'ville'               => 'Cotonou',
                'disponibilite'       => 'immediatement',
                'remote'              => 'partiel',
            ]);

        $this->assertDatabaseHas('candidat_profils', [
            'user_id'              => $candidat->id,
            'titre_professionnel'  => 'Développeur Full Stack',
            'ville'                => 'Cotonou',
        ]);
    }

    public function test_mise_a_jour_modifie_profil_candidat_existant(): void
    {
        $candidat = $this->creerCandidat();
        CandidatProfil::factory()->create([
            'user_id'             => $candidat->id,
            'titre_professionnel' => 'Ancien titre',
        ]);

        $this->actingAs($candidat)
            ->put(route('candidat.profil.update'), [
                'prenom'              => $candidat->prenom,
                'nom'                 => $candidat->nom,
                'titre_professionnel' => 'Nouveau titre',
            ]);

        $this->assertDatabaseHas('candidat_profils', [
            'user_id'             => $candidat->id,
            'titre_professionnel' => 'Nouveau titre',
        ]);
        $this->assertDatabaseCount('candidat_profils', 1);
    }

    public function test_mise_a_jour_types_contrat_en_json(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->put(route('candidat.profil.update'), [
                'prenom'        => $candidat->prenom,
                'nom'           => $candidat->nom,
                'types_contrat' => ['cdi', 'freelance'],
            ]);

        $profil = CandidatProfil::where('user_id', $candidat->id)->first();
        $this->assertContains('cdi', $profil->types_contrat);
        $this->assertContains('freelance', $profil->types_contrat);
    }

    // ── Validation ────────────────────────────────────────

    public function test_validation_prenom_requis(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->put(route('candidat.profil.update'), ['nom' => 'Mensah'])
            ->assertSessionHasErrors('prenom');
    }

    public function test_validation_nom_requis(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->put(route('candidat.profil.update'), ['prenom' => 'Kofi'])
            ->assertSessionHasErrors('nom');
    }

    public function test_validation_bio_trop_longue(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->put(route('candidat.profil.update'), [
                'prenom' => 'Kofi',
                'nom'    => 'Mensah',
                'bio'    => str_repeat('x', 1001),
            ])
            ->assertSessionHasErrors('bio');
    }

    public function test_validation_linkedin_url_invalide(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->put(route('candidat.profil.update'), [
                'prenom'   => 'Kofi',
                'nom'      => 'Mensah',
                'linkedin' => 'pas-une-url',
            ])
            ->assertSessionHasErrors('linkedin');
    }

    public function test_validation_salaire_min_negatif(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->put(route('candidat.profil.update'), [
                'prenom'      => 'Kofi',
                'nom'         => 'Mensah',
                'salaire_min' => -1000,
            ])
            ->assertSessionHasErrors('salaire_min');
    }

    public function test_validation_remote_valeur_invalide(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->put(route('candidat.profil.update'), [
                'prenom' => 'Kofi',
                'nom'    => 'Mensah',
                'remote' => 'toujours',
            ])
            ->assertSessionHasErrors('remote');
    }

    // ── Avatar ────────────────────────────────────────────

    public function test_upload_avatar_valide(): void
    {
        Storage::fake('public');
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->put(route('candidat.profil.update'), [
                'prenom' => $candidat->prenom,
                'nom'    => $candidat->nom,
                'avatar' => UploadedFile::fake()->image('photo.jpg', 200, 200),
            ])
            ->assertRedirect(route('candidat.profil'));

        $this->assertNotNull($candidat->fresh()->avatar);
        Storage::disk('public')->assertExists($candidat->fresh()->avatar);
    }

    public function test_upload_avatar_type_invalide(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->put(route('candidat.profil.update'), [
                'prenom' => $candidat->prenom,
                'nom'    => $candidat->nom,
                'avatar' => UploadedFile::fake()->create('document.pdf', 500, 'application/pdf'),
            ])
            ->assertSessionHasErrors('avatar');
    }

    public function test_suppression_avatar(): void
    {
        Storage::fake('public');
        $candidat = $this->creerCandidat();
        $candidat->update(['avatar' => 'avatars/test.jpg']);
        Storage::disk('public')->put('avatars/test.jpg', 'contenu');

        $this->actingAs($candidat)
            ->delete(route('candidat.profil.avatar.delete'))
            ->assertRedirect(route('candidat.profil'));

        $this->assertNull($candidat->fresh()->avatar);
    }

    public function test_suppression_avatar_sans_avatar_ne_plante_pas(): void
    {
        $candidat = $this->creerCandidat();
        $this->assertNull($candidat->avatar);

        $this->actingAs($candidat)
            ->delete(route('candidat.profil.avatar.delete'))
            ->assertRedirect(route('candidat.profil'));
    }
}
