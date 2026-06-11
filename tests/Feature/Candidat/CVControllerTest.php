<?php

namespace Tests\Feature\Candidat;

use App\Models\Abonnement;
use App\Models\CV;
use App\Models\Document;
use App\Models\TypeDocument;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CVControllerTest extends TestCase
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

    private function rendreCandidat(User $user): void
    {
        Abonnement::create([
            'user_id'   => $user->id,
            'plan'      => 'premium',
            'type'      => 'mensuel',
            'prix'      => 5000,
            'statut'    => 'actif',
            'debut_le'  => now()->subDay(),
            'expire_le' => now()->addMonth(),
        ]);
    }

    private function typeCV(): TypeDocument
    {
        return TypeDocument::create(['nom' => 'Curriculum Vitae (CV)', 'actif' => true, 'ordre' => 1]);
    }

    private function typeDoc(string $nom = 'Diplôme'): TypeDocument
    {
        return TypeDocument::create(['nom' => $nom, 'actif' => true, 'ordre' => 2]);
    }

    private function creerCV(User $candidat, array $attrs = []): CV
    {
        return CV::create(array_merge([
            'candidat_id' => $candidat->id,
            'titre_poste' => 'Développeur',
            'pays'        => 'Bénin',
            'plan'        => 'gratuit',
            'visible'     => false,
        ], $attrs));
    }

    // ── Accès à la liste (espace candidat) ───────────────

    public function test_page_cvs_accessible_au_candidat_connecte(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->get(route('candidat.cvs'))
            ->assertOk()
            ->assertViewIs('candidat.cvs');
    }

    public function test_page_cvs_redirige_si_non_connecte(): void
    {
        $this->get(route('candidat.cvs'))
            ->assertRedirect(route('auth.connexion'));
    }

    public function test_page_cvs_inaccessible_au_recruteur(): void
    {
        $recruteur = $this->creerAutreRole('recruteur');

        $this->actingAs($recruteur)
            ->get(route('candidat.cvs'))
            ->assertForbidden();
    }

    // ── Formulaire de dépôt public ────────────────────────

    public function test_page_depot_accessible_sans_connexion(): void
    {
        $this->typeCV();

        $this->get(route('cv.public.depot'))
            ->assertRedirect(route('auth.connexion'));
    }

    public function test_page_depot_accessible_au_candidat_sans_document(): void
    {
        $candidat = $this->creerCandidat();
        $this->typeCV();

        $this->actingAs($candidat)
            ->get(route('cv.public.depot'))
            ->assertOk();
    }

    public function test_page_depot_redirige_si_limite_gratuit_atteinte(): void
    {
        $candidat = $this->creerCandidat();
        $this->creerCV($candidat);

        $this->actingAs($candidat)
            ->get(route('cv.public.depot'))
            ->assertRedirect(route('candidat.abonnement'));
    }

    public function test_page_depot_accessible_si_premium_meme_avec_un_cv(): void
    {
        $candidat = $this->creerCandidat();
        $this->rendreCandidat($candidat);
        $this->creerCV($candidat);
        $this->typeCV();

        $this->actingAs($candidat)
            ->get(route('cv.public.depot'))
            ->assertOk();
    }

    // ── Création via dépôt public (store) ─────────────────

    public function test_store_cree_cv_avec_plan_gratuit_et_visible_false(): void
    {
        Storage::fake('public');
        $candidat = $this->creerCandidat();
        $typeCV   = $this->typeCV();

        $this->actingAs($candidat)
            ->post(route('cv.public.depot.store'), [
                'type_document_id' => $typeCV->id,
                'nom'              => 'Développeur Web',
                'pays'             => 'Bénin',
                'ville'            => 'Cotonou',
                'competences'      => 'PHP, Laravel',
                'experience'       => '3 ans',
                'formation'        => 'Licence Informatique',
                'langues'          => 'Français, Anglais',
            ])
            ->assertRedirect(route('candidat.cvs'));

        $this->assertDatabaseHas('cvs', [
            'candidat_id' => $candidat->id,
            'titre_poste' => 'Développeur Web',
            'plan'        => 'gratuit',
            'visible'     => false,
        ]);
    }

    public function test_store_cv_avec_fichier_le_stocke(): void
    {
        Storage::fake('public');
        $candidat = $this->creerCandidat();
        $typeCV   = $this->typeCV();

        $this->actingAs($candidat)
            ->post(route('cv.public.depot.store'), [
                'type_document_id' => $typeCV->id,
                'nom'              => 'Comptable',
                'pays'             => 'Bénin',
                'fichier_path'     => UploadedFile::fake()->create('mon-cv.pdf', 500, 'application/pdf'),
            ])
            ->assertRedirect(route('candidat.cvs'));

        $cv = CV::where('candidat_id', $candidat->id)->first();
        $this->assertNotNull($cv->fichier_path);
        Storage::disk('public')->assertExists($cv->fichier_path);
    }

    public function test_store_cree_document_si_type_non_cv(): void
    {
        Storage::fake('public');
        $candidat = $this->creerCandidat();
        $typeDoc  = $this->typeDoc('Diplôme');

        $this->actingAs($candidat)
            ->post(route('cv.public.depot.store'), [
                'type_document_id' => $typeDoc->id,
                'nom'              => 'Licence Informatique',
                'fichier_path'     => UploadedFile::fake()->create('diplome.pdf', 300, 'application/pdf'),
            ])
            ->assertRedirect(route('candidat.cvs'));

        $this->assertDatabaseHas('documents', [
            'user_id' => $candidat->id,
            'nom'     => 'Licence Informatique',
        ]);
        $this->assertDatabaseCount('cvs', 0);
    }

    public function test_store_redirige_si_limite_gratuit_atteinte(): void
    {
        $candidat = $this->creerCandidat();
        $typeCV   = $this->typeCV();
        $this->creerCV($candidat);

        $this->actingAs($candidat)
            ->post(route('cv.public.depot.store'), [
                'type_document_id' => $typeCV->id,
                'nom'              => 'Second CV',
            ])
            ->assertRedirect(route('candidat.abonnement'));

        $this->assertDatabaseCount('cvs', 1);
    }

    public function test_store_premium_peut_deposer_plusieurs_cvs(): void
    {
        Storage::fake('public');
        $candidat = $this->creerCandidat();
        $this->rendreCandidat($candidat);
        $this->creerCV($candidat);
        $typeCV = $this->typeCV();

        $this->actingAs($candidat)
            ->post(route('cv.public.depot.store'), [
                'type_document_id' => $typeCV->id,
                'nom'              => 'Deuxième CV',
                'pays'             => 'Bénin',
            ])
            ->assertRedirect(route('candidat.cvs'));

        $this->assertDatabaseCount('cvs', 2);
    }

    public function test_store_document_requiert_fichier(): void
    {
        $candidat = $this->creerCandidat();
        $typeDoc  = $this->typeDoc();

        $this->actingAs($candidat)
            ->post(route('cv.public.depot.store'), [
                'type_document_id' => $typeDoc->id,
                'nom'              => 'Diplôme sans fichier',
            ])
            ->assertSessionHasErrors('fichier_path');
    }

    public function test_store_validation_type_document_id_requis(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->post(route('cv.public.depot.store'), ['nom' => 'CV sans type'])
            ->assertSessionHasErrors('type_document_id');
    }

    public function test_store_validation_nom_requis(): void
    {
        $candidat = $this->creerCandidat();
        $typeCV   = $this->typeCV();

        $this->actingAs($candidat)
            ->post(route('cv.public.depot.store'), ['type_document_id' => $typeCV->id])
            ->assertSessionHasErrors('nom');
    }

    public function test_store_validation_fichier_type_invalide(): void
    {
        $candidat = $this->creerCandidat();
        $typeCV   = $this->typeCV();

        $this->actingAs($candidat)
            ->post(route('cv.public.depot.store'), [
                'type_document_id' => $typeCV->id,
                'nom'              => 'CV',
                'fichier_path'     => UploadedFile::fake()->create('script.exe', 100, 'application/octet-stream'),
            ])
            ->assertSessionHasErrors('fichier_path');
    }

    // ── Modification (edit / update) ──────────────────────

    public function test_edit_accessible_au_proprietaire(): void
    {
        $candidat = $this->creerCandidat();
        $cv       = $this->creerCV($candidat);

        $this->actingAs($candidat)
            ->get(route('candidat.cvs.edit', $cv))
            ->assertOk()
            ->assertViewIs('candidat.cv-edit');
    }

    public function test_edit_interdit_a_un_autre_candidat(): void
    {
        $proprietaire = $this->creerCandidat();
        $autre        = $this->creerCandidat();
        $cv           = $this->creerCV($proprietaire);

        $this->actingAs($autre)
            ->get(route('candidat.cvs.edit', $cv))
            ->assertForbidden();
    }

    public function test_update_modifie_le_cv(): void
    {
        $candidat = $this->creerCandidat();
        $cv       = $this->creerCV($candidat);

        $this->actingAs($candidat)
            ->put(route('candidat.cvs.update', $cv), [
                'titre_poste'  => 'Chef de projet',
                'pays'         => 'Bénin',
                'ville'        => 'Porto-Novo',
                'competences'  => 'Gestion de projet, Agile',
                'experience'   => '5 ans',
                'formation'    => 'Master Management',
                'langues'      => 'Français',
            ])
            ->assertRedirect(route('candidat.cvs'));

        $this->assertDatabaseHas('cvs', [
            'id'          => $cv->id,
            'titre_poste' => 'Chef de projet',
            'ville'       => 'Porto-Novo',
        ]);
    }

    public function test_update_remplace_fichier_et_supprime_lancien(): void
    {
        Storage::fake('public');
        $candidat = $this->creerCandidat();
        Storage::disk('public')->put('cvs/ancien.pdf', 'contenu');
        $cv = $this->creerCV($candidat, ['fichier_path' => 'cvs/ancien.pdf']);

        $this->actingAs($candidat)
            ->put(route('candidat.cvs.update', $cv), [
                'titre_poste'  => 'Développeur',
                'pays'         => 'Bénin',
                'fichier_path' => UploadedFile::fake()->create('nouveau.pdf', 400, 'application/pdf'),
            ])
            ->assertRedirect(route('candidat.cvs'));

        Storage::disk('public')->assertMissing('cvs/ancien.pdf');
        $this->assertNotEquals('cvs/ancien.pdf', $cv->fresh()->fichier_path);
    }

    public function test_update_interdit_a_un_autre_candidat(): void
    {
        $proprietaire = $this->creerCandidat();
        $autre        = $this->creerCandidat();
        $cv           = $this->creerCV($proprietaire);

        $this->actingAs($autre)
            ->put(route('candidat.cvs.update', $cv), [
                'titre_poste' => 'Hacker',
                'pays'        => 'Bénin',
            ])
            ->assertForbidden();
    }

    public function test_update_validation_titre_poste_requis(): void
    {
        $candidat = $this->creerCandidat();
        $cv       = $this->creerCV($candidat);

        $this->actingAs($candidat)
            ->put(route('candidat.cvs.update', $cv), ['pays' => 'Bénin'])
            ->assertSessionHasErrors('titre_poste');
    }

    public function test_update_validation_pays_requis(): void
    {
        $candidat = $this->creerCandidat();
        $cv       = $this->creerCV($candidat);

        $this->actingAs($candidat)
            ->put(route('candidat.cvs.update', $cv), ['titre_poste' => 'Dev'])
            ->assertSessionHasErrors('pays');
    }

    // ── Suppression ───────────────────────────────────────

    public function test_destroy_supprime_le_cv_du_proprietaire(): void
    {
        $candidat = $this->creerCandidat();
        $cv       = $this->creerCV($candidat);

        $this->actingAs($candidat)
            ->delete(route('candidat.cvs.destroy', $cv))
            ->assertRedirect(route('candidat.cvs'));

        $this->assertDatabaseMissing('cvs', ['id' => $cv->id]);
    }

    public function test_destroy_interdit_a_un_autre_candidat(): void
    {
        $proprietaire = $this->creerCandidat();
        $autre        = $this->creerCandidat();
        $cv           = $this->creerCV($proprietaire);

        $this->actingAs($autre)
            ->delete(route('candidat.cvs.destroy', $cv))
            ->assertForbidden();

        $this->assertDatabaseHas('cvs', ['id' => $cv->id]);
    }
}
