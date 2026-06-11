<?php

namespace Tests\Feature\Candidat;

use App\Models\Document;
use App\Models\TypeDocument;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentControllerTest extends TestCase
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

    private function typeDoc(string $nom = 'Diplôme'): TypeDocument
    {
        return TypeDocument::create(['nom' => $nom, 'actif' => true, 'ordre' => 1]);
    }

    private function creerDocument(User $user, TypeDocument $type, array $attrs = []): Document
    {
        return Document::create(array_merge([
            'user_id'          => $user->id,
            'type_document_id' => $type->id,
            'nom'              => 'Mon diplôme',
            'fichier'          => 'candidats/documents/test.pdf',
        ], $attrs));
    }

    private function fichierPdf(string $nom = 'doc.pdf'): UploadedFile
    {
        return UploadedFile::fake()->create($nom, 300, 'application/pdf');
    }

    // ── Création (store) ──────────────────────────────────

    public function test_store_cree_document(): void
    {
        Storage::fake('public');
        $candidat = $this->creerCandidat();
        $type     = $this->typeDoc();

        $this->actingAs($candidat)
            ->post(route('candidat.documents.store'), [
                'type_document_id' => $type->id,
                'nom'              => 'Licence Informatique',
                'fichier'          => $this->fichierPdf('licence.pdf'),
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('documents', [
            'user_id' => $candidat->id,
            'nom'     => 'Licence Informatique',
        ]);
    }

    public function test_store_stocke_le_fichier(): void
    {
        Storage::fake('public');
        $candidat = $this->creerCandidat();
        $type     = $this->typeDoc();

        $this->actingAs($candidat)
            ->post(route('candidat.documents.store'), [
                'type_document_id' => $type->id,
                'nom'              => 'Attestation',
                'fichier'          => $this->fichierPdf(),
            ]);

        $doc = Document::where('user_id', $candidat->id)->first();
        $this->assertNotNull($doc->fichier);
        Storage::disk('public')->assertExists($doc->fichier);
    }

    public function test_store_bloque_apres_15_documents(): void
    {
        Storage::fake('public');
        $candidat = $this->creerCandidat();
        $type     = $this->typeDoc();

        for ($i = 0; $i < 15; $i++) {
            Storage::disk('public')->put("candidats/documents/doc{$i}.pdf", 'x');
            $this->creerDocument($candidat, $type, [
                'nom'     => "Document {$i}",
                'fichier' => "candidats/documents/doc{$i}.pdf",
            ]);
        }

        $this->actingAs($candidat)
            ->post(route('candidat.documents.store'), [
                'type_document_id' => $type->id,
                'nom'              => 'Document 16',
                'fichier'          => $this->fichierPdf(),
            ])
            ->assertSessionHasErrors('fichier');

        $this->assertDatabaseCount('documents', 15);
    }

    public function test_store_validation_fichier_requis(): void
    {
        $candidat = $this->creerCandidat();
        $type     = $this->typeDoc();

        $this->actingAs($candidat)
            ->post(route('candidat.documents.store'), [
                'type_document_id' => $type->id,
                'nom'              => 'Sans fichier',
            ])
            ->assertSessionHasErrors('fichier');
    }

    public function test_store_validation_type_document_requis(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->post(route('candidat.documents.store'), [
                'nom'    => 'Diplôme',
                'fichier' => $this->fichierPdf(),
            ])
            ->assertSessionHasErrors('type_document_id');
    }

    public function test_store_validation_nom_requis(): void
    {
        $candidat = $this->creerCandidat();
        $type     = $this->typeDoc();

        $this->actingAs($candidat)
            ->post(route('candidat.documents.store'), [
                'type_document_id' => $type->id,
                'fichier'          => $this->fichierPdf(),
            ])
            ->assertSessionHasErrors('nom');
    }

    public function test_store_validation_fichier_type_invalide(): void
    {
        $candidat = $this->creerCandidat();
        $type     = $this->typeDoc();

        $this->actingAs($candidat)
            ->post(route('candidat.documents.store'), [
                'type_document_id' => $type->id,
                'nom'              => 'Virus',
                'fichier'          => UploadedFile::fake()->create('virus.exe', 100, 'application/octet-stream'),
            ])
            ->assertSessionHasErrors('fichier');
    }

    public function test_store_redirige_si_non_connecte(): void
    {
        $this->post(route('candidat.documents.store'), [])
            ->assertRedirect(route('auth.connexion'));
    }

    // ── Modification (edit / update) ──────────────────────

    public function test_edit_accessible_au_proprietaire(): void
    {
        $candidat = $this->creerCandidat();
        $type     = $this->typeDoc();
        $doc      = $this->creerDocument($candidat, $type);

        $this->actingAs($candidat)
            ->get(route('candidat.documents.edit', $doc))
            ->assertOk()
            ->assertViewIs('candidat.document-edit');
    }

    public function test_edit_interdit_a_un_autre_utilisateur(): void
    {
        $proprietaire = $this->creerCandidat();
        $autre        = $this->creerCandidat();
        $type         = $this->typeDoc();
        $doc          = $this->creerDocument($proprietaire, $type);

        $this->actingAs($autre)
            ->get(route('candidat.documents.edit', $doc))
            ->assertForbidden();
    }

    public function test_update_modifie_le_document(): void
    {
        Storage::fake('public');
        $candidat = $this->creerCandidat();
        $type     = $this->typeDoc();
        $type2    = $this->typeDoc('Attestation de formation');
        $doc      = $this->creerDocument($candidat, $type);

        $this->actingAs($candidat)
            ->put(route('candidat.documents.update', $doc), [
                'type_document_id' => $type2->id,
                'nom'              => 'Attestation Laravel',
                'pays'             => 'Bénin',
                'ville'            => 'Cotonou',
                'competences'      => 'Laravel',
                'experience'       => '2 ans',
                'formation'        => 'Formation en ligne',
                'langues'          => 'Français',
            ])
            ->assertRedirect(route('candidat.cvs'));

        $this->assertDatabaseHas('documents', [
            'id'               => $doc->id,
            'type_document_id' => $type2->id,
            'nom'              => 'Attestation Laravel',
            'ville'            => 'Cotonou',
        ]);
    }

    public function test_update_remplace_fichier_et_supprime_lancien(): void
    {
        Storage::fake('public');
        $candidat = $this->creerCandidat();
        $type     = $this->typeDoc();
        Storage::disk('public')->put('candidats/documents/ancien.pdf', 'contenu');
        $doc = $this->creerDocument($candidat, $type, ['fichier' => 'candidats/documents/ancien.pdf']);

        $this->actingAs($candidat)
            ->put(route('candidat.documents.update', $doc), [
                'type_document_id' => $type->id,
                'nom'              => 'Nouveau nom',
                'fichier'          => $this->fichierPdf('nouveau.pdf'),
            ])
            ->assertRedirect(route('candidat.cvs'));

        Storage::disk('public')->assertMissing('candidats/documents/ancien.pdf');
        $this->assertNotEquals('candidats/documents/ancien.pdf', $doc->fresh()->fichier);
    }

    public function test_update_sans_nouveau_fichier_conserve_lancien(): void
    {
        Storage::fake('public');
        $candidat = $this->creerCandidat();
        $type     = $this->typeDoc();
        Storage::disk('public')->put('candidats/documents/stable.pdf', 'contenu');
        $doc = $this->creerDocument($candidat, $type, ['fichier' => 'candidats/documents/stable.pdf']);

        $this->actingAs($candidat)
            ->put(route('candidat.documents.update', $doc), [
                'type_document_id' => $type->id,
                'nom'              => 'Nom mis à jour',
            ])
            ->assertRedirect(route('candidat.cvs'));

        $this->assertEquals('candidats/documents/stable.pdf', $doc->fresh()->fichier);
        Storage::disk('public')->assertExists('candidats/documents/stable.pdf');
    }

    public function test_update_interdit_a_un_autre_utilisateur(): void
    {
        $proprietaire = $this->creerCandidat();
        $autre        = $this->creerCandidat();
        $type         = $this->typeDoc();
        $doc          = $this->creerDocument($proprietaire, $type);

        $this->actingAs($autre)
            ->put(route('candidat.documents.update', $doc), [
                'type_document_id' => $type->id,
                'nom'              => 'Tentative',
            ])
            ->assertForbidden();
    }

    public function test_update_validation_nom_requis(): void
    {
        $candidat = $this->creerCandidat();
        $type     = $this->typeDoc();
        $doc      = $this->creerDocument($candidat, $type);

        $this->actingAs($candidat)
            ->put(route('candidat.documents.update', $doc), [
                'type_document_id' => $type->id,
            ])
            ->assertSessionHasErrors('nom');
    }

    // ── Suppression ───────────────────────────────────────

    public function test_destroy_supprime_le_document_et_le_fichier(): void
    {
        Storage::fake('public');
        $candidat = $this->creerCandidat();
        $type     = $this->typeDoc();
        Storage::disk('public')->put('candidats/documents/a-supprimer.pdf', 'contenu');
        $doc = $this->creerDocument($candidat, $type, ['fichier' => 'candidats/documents/a-supprimer.pdf']);

        $this->actingAs($candidat)
            ->delete(route('candidat.documents.destroy', $doc))
            ->assertRedirect();

        $this->assertDatabaseMissing('documents', ['id' => $doc->id]);
        Storage::disk('public')->assertMissing('candidats/documents/a-supprimer.pdf');
    }

    public function test_destroy_interdit_a_un_autre_utilisateur(): void
    {
        Storage::fake('public');
        $proprietaire = $this->creerCandidat();
        $autre        = $this->creerCandidat();
        $type         = $this->typeDoc();
        Storage::disk('public')->put('candidats/documents/protege.pdf', 'contenu');
        $doc = $this->creerDocument($proprietaire, $type, ['fichier' => 'candidats/documents/protege.pdf']);

        $this->actingAs($autre)
            ->delete(route('candidat.documents.destroy', $doc))
            ->assertForbidden();

        $this->assertDatabaseHas('documents', ['id' => $doc->id]);
    }
}
