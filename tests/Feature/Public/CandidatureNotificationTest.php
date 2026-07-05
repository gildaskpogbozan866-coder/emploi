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
use App\Notifications\NouvellesCandidatureRecruteurNotification;
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

    public function test_soumission_envoie_email_au_recruteur(): void
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
            $recruteur,
            NouvellesCandidatureRecruteurNotification::class,
        );
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
        $this->assertNotNull($candidature->cv_id);

        // Le fichier devient un vrai CV dans l'espace du candidat (masqué par défaut,
        // le candidat doit le compléter/publier lui-même), pas juste un chemin
        // rattaché à cette seule candidature.
        $cv = \App\Models\CV::find($candidature->cv_id);
        $this->assertNotNull($cv);
        $this->assertSame($candidat->id, $cv->candidat_id);
        $this->assertFalse($cv->visible);
        Storage::disk('public')->assertExists($cv->fichier_path);
    }

    public function test_candidature_garde_un_instantane_du_cv_meme_apres_modification(): void
    {
        Storage::fake('public');
        NotificationFacade::fake();

        $recruteur = $this->creerRecruteur();
        $candidat  = $this->creerCandidat();
        $offre     = $this->creerOffre($recruteur);

        Storage::disk('public')->put('cvs/original.pdf', 'contenu original');
        $cv = CV::create([
            'candidat_id'  => $candidat->id,
            'metier'       => 'Comptable',
            'ville'        => 'Cotonou',
            'fichier_path' => 'cvs/original.pdf',
            'visible'      => true,
            'publie_le'    => now(),
        ]);

        $this->actingAs($candidat)
            ->post(route('offre.postuler.store', $offre), ['cv_id' => $cv->id]);

        $candidature = Candidature::where('candidat_id', $candidat->id)->first();
        $this->assertNotNull($candidature->cv_snapshot);
        $this->assertSame('Comptable', $candidature->cv_snapshot['metier']);
        $this->assertSame('Cotonou', $candidature->cv_snapshot['ville']);
        // Le fichier de l'instantané est une copie distincte, pas le même chemin.
        $this->assertNotEquals('cvs/original.pdf', $candidature->cv_snapshot['fichier_path']);
        Storage::disk('public')->assertExists($candidature->cv_snapshot['fichier_path']);

        // Le candidat modifie ensuite son CV (métier, ville, fichier remplacé —
        // l'ancien fichier physique est supprimé, comme le fait vraiment update()).
        Storage::disk('public')->delete('cvs/original.pdf');
        Storage::disk('public')->put('cvs/nouveau.pdf', 'nouveau contenu');
        $cv->update(['metier' => 'Développeur', 'ville' => 'Porto-Novo', 'fichier_path' => 'cvs/nouveau.pdf']);

        // La candidature déjà envoyée continue d'afficher l'ancien état, pas le nouveau.
        $candidature->refresh();
        $this->assertSame('Comptable', $candidature->cv_snapshot['metier']);
        $this->assertSame('Cotonou', $candidature->cv_snapshot['ville']);

        $this->actingAs($recruteur)
            ->get(route('recruteur.candidatures.show', $candidature))
            ->assertSee('Comptable', false)
            ->assertDontSee('Développeur', false);

        $this->actingAs($candidat)
            ->get(route('candidat.candidatures.detail', $candidature))
            ->assertSee('Comptable', false)
            ->assertDontSee('Développeur', false);

        // Une nouvelle candidature (autre offre), elle, reflète bien le nouvel état.
        $autreOffre = $this->creerOffre($recruteur);
        $this->actingAs($candidat)
            ->post(route('offre.postuler.store', $autreOffre), ['cv_id' => $cv->id]);

        $nouvelleCandidature = Candidature::where('offre_id', $autreOffre->id)->first();
        $this->assertSame('Développeur', $nouvelleCandidature->cv_snapshot['metier']);
        $this->assertSame('Porto-Novo', $nouvelleCandidature->cv_snapshot['ville']);
    }

    public function test_page_succes_invite_a_completer_le_cv_nouvellement_cree(): void
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
            ->assertSessionHas('nouveau_cv_id');

        $cv = \App\Models\CV::where('candidat_id', $candidat->id)->first();

        $this->actingAs($candidat)
            ->withSession(['nouveau_cv_id' => $cv->id])
            ->get(route('offre.candidature-succes', $offre))
            ->assertSee('Complétez-le', false)
            ->assertSee(route('candidat.cvs.edit', $cv), false);
    }

    public function test_page_succes_sans_encart_si_cv_existant_reutilise(): void
    {
        NotificationFacade::fake();

        $recruteur = $this->creerRecruteur();
        $candidat  = $this->creerCandidat();
        $offre     = $this->creerOffre($recruteur);
        $cv        = CV::create(['candidat_id' => $candidat->id, 'titre_poste' => 'Dev', 'pays' => 'BJ']);

        $this->actingAs($candidat)
            ->post(route('offre.postuler.store', $offre), ['cv_id' => $cv->id])
            ->assertSessionMissing('nouveau_cv_id');

        $this->actingAs($candidat)
            ->get(route('offre.candidature-succes', $offre))
            ->assertDontSee('Complétez-le', false);
    }

    public function test_storerCandidature_bloque_upload_cv_si_quota_atteint(): void
    {
        Storage::fake('public');
        NotificationFacade::fake();

        $recruteur = $this->creerRecruteur();
        $candidat  = $this->creerCandidat();
        $offre     = $this->creerOffre($recruteur);

        // Plan par défaut (aucun abonnement) = limite de 1 CV.
        \App\Models\CV::create(['candidat_id' => $candidat->id]);

        $this->actingAs($candidat)
            ->post(route('offre.postuler.store', $offre), [
                'cv_file' => UploadedFile::fake()->create('cv.pdf', 200, 'application/pdf'),
            ])
            ->assertSessionHasErrors('cv_file');

        $this->assertDatabaseMissing('candidatures', ['candidat_id' => $candidat->id]);
        $this->assertSame(1, \App\Models\CV::where('candidat_id', $candidat->id)->count());
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

    // ── Pièces justificatives ─────────────────────────────

    public function test_storerCandidature_attache_les_pieces_justificatives_du_candidat(): void
    {
        NotificationFacade::fake();

        $recruteur = $this->creerRecruteur();
        $candidat  = $this->creerCandidat();
        $offre     = $this->creerOffre($recruteur);
        $type      = \App\Models\TypeDocument::create(['nom' => 'Diplôme', 'actif' => true, 'ordre' => 1]);
        $doc       = \App\Models\Document::create([
            'user_id'          => $candidat->id,
            'type_document_id' => $type->id,
            'nom'              => 'Licence Informatique',
            'fichier'          => 'candidats/documents/test.pdf',
        ]);

        $this->actingAs($candidat)
            ->post(route('offre.postuler.store', $offre), [
                'message_motivation' => 'Motivé !',
                'pieces_ids'         => [$doc->id],
            ])
            ->assertRedirect(route('offre.candidature-succes', $offre));

        $candidature = Candidature::where('candidat_id', $candidat->id)->first();
        $this->assertTrue($candidature->documents->contains($doc->id));
    }

    public function test_storerCandidature_ignore_piece_appartenant_a_un_autre_candidat(): void
    {
        NotificationFacade::fake();

        $recruteur = $this->creerRecruteur();
        $candidat  = $this->creerCandidat();
        $autre     = $this->creerCandidat();
        $offre     = $this->creerOffre($recruteur);
        $type      = \App\Models\TypeDocument::create(['nom' => 'Diplôme', 'actif' => true, 'ordre' => 1]);
        $docAutre  = \App\Models\Document::create([
            'user_id'          => $autre->id,
            'type_document_id' => $type->id,
            'nom'              => 'Diplôme d\'un autre',
            'fichier'          => 'candidats/documents/autre.pdf',
        ]);

        $this->actingAs($candidat)
            ->post(route('offre.postuler.store', $offre), [
                'message_motivation' => 'Motivé !',
                'pieces_ids'         => [$docAutre->id],
            ]);

        $candidature = Candidature::where('candidat_id', $candidat->id)->first();
        $this->assertNotNull($candidature);
        $this->assertFalse($candidature->documents->contains($docAutre->id), 'Une pièce appartenant à un autre candidat ne doit pas être attachée');
    }

    // ── Documents requis par l'offre ──────────────────────

    public function test_postuler_affiche_les_documents_requis_avec_choix_existant(): void
    {
        $recruteur = $this->creerRecruteur();
        $candidat  = $this->creerCandidat();
        $offre     = $this->creerOffre($recruteur);
        $type      = \App\Models\TypeDocument::create(['nom' => 'Diplôme', 'actif' => true, 'ordre' => 1]);
        $offre->typesDocumentsRequis()->attach($type->id);
        \App\Models\Document::create([
            'user_id'          => $candidat->id,
            'type_document_id' => $type->id,
            'nom'              => 'Licence Informatique',
            'fichier'          => 'candidats/documents/test.pdf',
        ]);

        $this->actingAs($candidat)
            ->get(route('offre.postuler', $offre))
            ->assertOk()
            ->assertSee('Diplôme')
            ->assertSee('Licence Informatique');
    }

    public function test_storerCandidature_echoue_si_document_requis_manquant(): void
    {
        NotificationFacade::fake();

        $recruteur = $this->creerRecruteur();
        $candidat  = $this->creerCandidat();
        $offre     = $this->creerOffre($recruteur);
        $type      = \App\Models\TypeDocument::create(['nom' => 'Diplôme', 'actif' => true, 'ordre' => 1]);
        $offre->typesDocumentsRequis()->attach($type->id);

        $this->actingAs($candidat)
            ->post(route('offre.postuler.store', $offre), [
                'message_motivation' => 'Motivé !',
            ])
            ->assertSessionHasErrors("pieces_nouvelles.{$type->id}");

        $this->assertDatabaseMissing('candidatures', ['candidat_id' => $candidat->id]);
    }

    public function test_storerCandidature_accepte_document_requis_existant(): void
    {
        NotificationFacade::fake();

        $recruteur = $this->creerRecruteur();
        $candidat  = $this->creerCandidat();
        $offre     = $this->creerOffre($recruteur);
        $type      = \App\Models\TypeDocument::create(['nom' => 'Diplôme', 'actif' => true, 'ordre' => 1]);
        $offre->typesDocumentsRequis()->attach($type->id);
        $doc = \App\Models\Document::create([
            'user_id'          => $candidat->id,
            'type_document_id' => $type->id,
            'nom'              => 'Licence Informatique',
            'fichier'          => 'candidats/documents/test.pdf',
        ]);

        $this->actingAs($candidat)
            ->post(route('offre.postuler.store', $offre), [
                'message_motivation' => 'Motivé !',
                'pieces_existantes'  => [$type->id => $doc->id],
            ])
            ->assertRedirect(route('offre.candidature-succes', $offre));

        $candidature = Candidature::where('candidat_id', $candidat->id)->first();
        $this->assertTrue($candidature->documents->contains($doc->id));
        // Pas de doublon créé : le document existant a été réutilisé.
        $this->assertDatabaseCount('documents', 1);
    }

    public function test_storerCandidature_uploade_nouveau_document_requis_et_le_sauvegarde_dans_lespace_candidat(): void
    {
        Storage::fake('public');
        NotificationFacade::fake();

        $recruteur = $this->creerRecruteur();
        $candidat  = $this->creerCandidat();
        $offre     = $this->creerOffre($recruteur);
        $type      = \App\Models\TypeDocument::create(['nom' => 'Diplôme', 'actif' => true, 'ordre' => 1]);
        $offre->typesDocumentsRequis()->attach($type->id);

        $this->actingAs($candidat)
            ->post(route('offre.postuler.store', $offre), [
                'message_motivation' => 'Motivé !',
                'pieces_nouvelles'   => [$type->id => UploadedFile::fake()->create('diplome.pdf', 200, 'application/pdf')],
            ])
            ->assertRedirect(route('offre.candidature-succes', $offre));

        $candidature = Candidature::where('candidat_id', $candidat->id)->first();
        $this->assertCount(1, $candidature->documents);

        // Le nouveau document doit aussi exister durablement dans l'espace du candidat.
        $this->assertDatabaseHas('documents', [
            'user_id'          => $candidat->id,
            'type_document_id' => $type->id,
        ]);
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
