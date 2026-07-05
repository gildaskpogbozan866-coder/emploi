<?php

namespace Tests\Feature\Candidat;

use App\Models\Abonnement;
use App\Models\CV;
use App\Models\Document;
use App\Models\PlanFeature;
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
        $plan = \App\Models\Plan::create([
            'name'          => 'Premium Candidat',
            'slug'          => 'premium-candidat',
            'target_type'   => 'candidat',
            'price'         => 5000,
            'duration_days' => 30,
            'is_free'       => false,
            'is_active'     => true,
        ]);
        // cv_limit = 0 signifie illimité dans le contrôleur
        \App\Models\PlanFeature::create(['plan_id' => $plan->id, 'feature_key' => 'cv_limit', 'feature_value' => '0']);
        Abonnement::create([
            'user_id'   => $user->id,
            'plan_id'   => $plan->id,
            'starts_at' => now()->subDay(),
            'ends_at'   => now()->addMonth(),
            'status'    => 'active',
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

    /**
     * Champs devenus obligatoires pour un dépôt de type CV uniquement.
     */
    private function champsCvObligatoires(): array
    {
        $typeContrat = \App\Models\TypeContrat::create(['code' => 'cdi', 'libelle' => 'CDI']);
        $niveauEtude = \App\Models\NiveauEtude::create(['code' => 'licence', 'libelle' => 'Licence', 'ordre' => 1]);
        $niveauExp   = \App\Models\NiveauExperience::create(['code' => '1-3-ans', 'libelle' => '1-3 ans', 'ordre' => 1]);
        $langue      = \App\Models\Langue::create(['nom' => 'Français']);
        $niveauLangue = \App\Models\NiveauLangue::create(['code' => 'courant', 'libelle' => 'Courant', 'ordre' => 1]);

        return [
            'types_contrat_ids'    => [$typeContrat->id],
            'niveau_etude_id'      => $niveauEtude->id,
            'niveau_experience_id' => $niveauExp->id,
            'competences'          => 'PHP, Laravel',
            'resume'               => 'Développeur passionné avec 3 ans d\'expérience.',
            'experience'           => 'Développeur — MTN Bénin | Cotonou | jan. 2022 → en cours',
            'formation'            => 'Licence Informatique — UAC | 2019 → 2022',
            'langues_ids'          => [$langue->id],
            'niveaux_ids'          => [$niveauLangue->id],
            'photo'                => $this->fakeImage(),
        ];
    }

    private function fakeImage(string $nom = 'photo.png'): UploadedFile
    {
        $png  = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=');
        $path = tempnam(sys_get_temp_dir(), 'photo') . '.png';
        file_put_contents($path, $png);

        return new UploadedFile($path, $nom, 'image/png', null, true);
    }

    private function creerCV(User $candidat, array $attrs = []): CV
    {
        return CV::create(array_merge([
            'candidat_id' => $candidat->id,
            'titre_poste' => 'Développeur',
            'pays'        => 'Bénin',
            'plan'        => 'gratuit',
            'visible'     => true,
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

    public function test_page_cvs_affiche_cv_et_documents_regroupes_separement(): void
    {
        $candidat = $this->creerCandidat();
        $this->creerCV($candidat);
        $type = \App\Models\TypeDocument::create(['nom' => 'Diplôme', 'actif' => true, 'ordre' => 1]);
        \App\Models\Document::create([
            'user_id'          => $candidat->id,
            'type_document_id' => $type->id,
            'nom'              => 'Licence Informatique',
            'fichier'          => 'candidats/documents/test.pdf',
        ]);

        $this->actingAs($candidat)
            ->get(route('candidat.cvs'))
            ->assertOk()
            ->assertSee('Mes CV')
            ->assertSee('Mes autres documents')
            ->assertSee('Licence Informatique');
    }

    public function test_page_cvs_numerote_les_cv_par_ordre_de_depot(): void
    {
        $candidat = $this->creerCandidat();
        $premier = $this->creerCV($candidat);
        $second  = $this->creerCV($candidat);
        // created_at n'est pas mass-assignable — on force l'ordre chronologique
        // pour que le tri "plus récent d'abord" de la page produise un ordre connu.
        CV::where('id', $premier->id)->update(['created_at' => now()->subDays(2)]);
        CV::where('id', $second->id)->update(['created_at' => now()->subDay()]);

        // Le CV déposé en premier doit porter le n°1, peu importe que la liste
        // affiche le plus récent en premier.
        $this->actingAs($candidat)
            ->get(route('candidat.cvs'))
            ->assertOk()
            ->assertSee('CV n°1')
            ->assertSee('CV n°2');
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

    public function test_page_depot_reste_accessible_si_limite_cv_gratuit_atteinte(): void
    {
        // Le quota ne limite que les CV — la page doit rester accessible pour
        // permettre de déposer un document (diplôme, attestation...).
        $candidat = $this->creerCandidat();
        $this->creerCV($candidat);

        $this->actingAs($candidat)
            ->get(route('cv.public.depot'))
            ->assertOk();
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

    public function test_store_cree_cv_avec_plan_gratuit_et_visible_true(): void
    {
        Storage::fake('public');
        $candidat = $this->creerCandidat();
        $typeCV   = $this->typeCV();

        $this->actingAs($candidat)
            ->post(route('cv.public.depot.store'), array_merge($this->champsCvObligatoires(), [
                'type_document_id' => $typeCV->id,
                'prenom'           => $candidat->prenom,
                'nom_famille'      => $candidat->nom,
                'tel'              => '+229 90000000',
                'disponibilite'    => 'immediatement',
                'nom'              => 'Développeur Web',
                'pays'             => 'Bénin',
                'ville'            => 'Cotonou',
                'experience'       => '3 ans',
                'formation'        => 'Licence Informatique',
                'langues'          => 'Français, Anglais',
                'fichier_path'     => UploadedFile::fake()->create('cv.pdf', 400, 'application/pdf'),
            ]))
            ->assertRedirect(route('candidat.cvs'));

        $this->assertDatabaseHas('cvs', [
            'candidat_id' => $candidat->id,
            'metier'      => 'Développeur Web',
            'plan'        => 'gratuit',
            'visible'     => true,
        ]);
    }

    public function test_store_cv_avec_fichier_le_stocke(): void
    {
        Storage::fake('public');
        $candidat = $this->creerCandidat();
        $typeCV   = $this->typeCV();

        $this->actingAs($candidat)
            ->post(route('cv.public.depot.store'), array_merge($this->champsCvObligatoires(), [
                'type_document_id' => $typeCV->id,
                'prenom'           => $candidat->prenom,
                'nom_famille'      => $candidat->nom,
                'tel'              => '+229 90000000',
                'disponibilite'    => 'immediatement',
                'ville'            => 'Cotonou',
                'nom'              => 'Comptable',
                'pays'             => 'Bénin',
                'fichier_path'     => UploadedFile::fake()->create('mon-cv.pdf', 500, 'application/pdf'),
            ]))
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
                'prenom'           => $candidat->prenom,
                'nom_famille'      => $candidat->nom,
                'tel'              => '+229 90000000',
                'disponibilite'    => 'immediatement',
                'ville'            => 'Cotonou',
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

    public function test_store_document_reussit_meme_si_limite_cv_gratuit_atteinte(): void
    {
        // Le quota gratuit (1 CV) ne doit jamais bloquer le dépôt d'un document
        // (diplôme, attestation...), seulement celui d'un CV supplémentaire.
        Storage::fake('public');
        $candidat = $this->creerCandidat();
        $this->creerCV($candidat);
        $typeDoc = $this->typeDoc('Diplôme');

        $this->actingAs($candidat)
            ->post(route('cv.public.depot.store'), [
                'type_document_id' => $typeDoc->id,
                'prenom'           => $candidat->prenom,
                'nom_famille'      => $candidat->nom,
                'tel'              => '+229 90000000',
                'disponibilite'    => 'immediatement',
                'ville'            => 'Cotonou',
                'nom'              => 'Licence Informatique',
                'fichier_path'     => UploadedFile::fake()->create('diplome.pdf', 300, 'application/pdf'),
            ])
            ->assertRedirect(route('candidat.cvs'));

        $this->assertDatabaseHas('documents', [
            'user_id' => $candidat->id,
            'nom'     => 'Licence Informatique',
        ]);
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
            ->assertRedirect(route('candidat.abonnement.plans'));

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
            ->post(route('cv.public.depot.store'), array_merge($this->champsCvObligatoires(), [
                'type_document_id' => $typeCV->id,
                'prenom'           => $candidat->prenom,
                'nom_famille'      => $candidat->nom,
                'tel'              => '+229 90000000',
                'disponibilite'    => 'immediatement',
                'ville'            => 'Cotonou',
                'nom'              => 'Deuxième CV',
                'pays'             => 'Bénin',
                'fichier_path'     => UploadedFile::fake()->create('cv2.pdf', 400, 'application/pdf'),
            ]))
            ->assertRedirect(route('candidat.cvs'));

        $this->assertDatabaseCount('cvs', 2);
    }

    /**
     * Décision explicite du client : dès qu'un abonnement épuise l'un de ses
     * avantages (ici le quota CV), le suivant déjà souscrit prend le relais
     * immédiatement — testé ici via le vrai flux HTTP de dépôt, pas juste le
     * service en isolation.
     */
    public function test_store_reussit_via_relais_anticipe_si_ancien_plan_epuise(): void
    {
        Storage::fake('public');
        $candidat = $this->creerCandidat();

        $ancienPlan = \App\Models\Plan::create([
            'name' => 'Basique', 'slug' => 'basique-'.uniqid(), 'target_type' => 'candidat',
            'price' => 1000, 'duration_days' => 30, 'is_free' => false, 'is_active' => true,
        ]);
        \App\Models\PlanFeature::create(['plan_id' => $ancienPlan->id, 'feature_key' => 'cv_limit', 'feature_value' => '1']);
        $ancien = Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $ancienPlan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => now()->addDays(10),
        ]);
        $this->creerCV($candidat); // épuise le quota de 1 CV du plan actuel

        $nouveauPlan = \App\Models\Plan::create([
            'name' => 'Premium', 'slug' => 'premium-'.uniqid(), 'target_type' => 'candidat',
            'price' => 5000, 'duration_days' => 30, 'is_free' => false, 'is_active' => true,
        ]);
        \App\Models\PlanFeature::create(['plan_id' => $nouveauPlan->id, 'feature_key' => 'cv_limit', 'feature_value' => '5']);
        Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $nouveauPlan->id, 'status' => 'active',
            'starts_at' => now()->addDays(9), 'ends_at' => now()->addDays(39),
        ]);

        $typeCV = $this->typeCV();

        $this->actingAs($candidat)
            ->post(route('cv.public.depot.store'), array_merge($this->champsCvObligatoires(), [
                'type_document_id' => $typeCV->id,
                'prenom'           => $candidat->prenom,
                'nom_famille'      => $candidat->nom,
                'tel'              => '+229 90000000',
                'disponibilite'    => 'immediatement',
                'ville'            => 'Cotonou',
                'nom'              => 'Deuxième CV',
                'pays'             => 'Bénin',
                'fichier_path'     => UploadedFile::fake()->create('cv2.pdf', 400, 'application/pdf'),
            ]))
            ->assertRedirect(route('candidat.cvs'));

        $this->assertDatabaseCount('cvs', 2);
        $this->assertTrue($ancien->fresh()->ends_at->lessThanOrEqualTo(now()));
        $this->assertSame($nouveauPlan->id, $candidat->fresh()->abonnementActif()->first()->plan_id);
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

    public function test_store_cv_sans_niveau_etude_experience_competences_type_contrat_echoue(): void
    {
        $candidat = $this->creerCandidat();
        $typeCV   = $this->typeCV();

        $this->actingAs($candidat)
            ->post(route('cv.public.depot.store'), [
                'type_document_id' => $typeCV->id,
                'prenom'           => $candidat->prenom,
                'nom_famille'      => $candidat->nom,
                'tel'              => '+229 90000000',
                'disponibilite'    => 'immediatement',
                'ville'            => 'Cotonou',
                'nom'              => 'Comptable',
                'fichier_path'     => UploadedFile::fake()->create('cv.pdf', 400, 'application/pdf'),
            ])
            ->assertSessionHasErrors([
                'types_contrat_ids', 'niveau_etude_id', 'niveau_experience_id', 'competences',
                'resume', 'experience', 'formation', 'langues_ids', 'pays',
            ]);
    }

    public function test_store_document_non_cv_sans_ces_champs_reussit(): void
    {
        Storage::fake('public');
        $candidat = $this->creerCandidat();
        $typeDoc  = $this->typeDoc('Diplôme');

        $this->actingAs($candidat)
            ->post(route('cv.public.depot.store'), [
                'type_document_id' => $typeDoc->id,
                'prenom'           => $candidat->prenom,
                'nom_famille'      => $candidat->nom,
                'tel'              => '+229 90000000',
                'disponibilite'    => 'immediatement',
                'ville'            => 'Cotonou',
                'nom'              => 'Licence Informatique',
                'fichier_path'     => UploadedFile::fake()->create('diplome.pdf', 300, 'application/pdf'),
            ])
            ->assertSessionDoesntHaveErrors(['types_contrat_ids', 'niveau_etude_id', 'niveau_experience_id', 'competences'])
            ->assertRedirect(route('candidat.cvs'));
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

    /**
     * Charge utile complète et valide pour update() — mêmes règles obligatoires
     * que le dépôt initial (store()), mais avec les noms de champs d'update()
     * (types_contrat_ids/niveau_etude_id/niveau_experience_id restent des IDs,
     * convertis en texte côté serveur comme au dépôt).
     */
    private function champsCvUpdateValides(): array
    {
        return array_merge($this->champsCvObligatoires(), [
            'ville'  => 'Porto-Novo',
            'metier' => 'Développeur',
            'resume' => 'Développeur passionné avec 3 ans d\'expérience.',
        ]);
    }

    public function test_update_modifie_le_cv(): void
    {
        Storage::fake('public');
        $candidat = $this->creerCandidat();
        $cv       = $this->creerCV($candidat, ['photo' => 'cvs/photos/existante.jpg']);

        $this->actingAs($candidat)
            ->put(route('candidat.cvs.update', $cv), array_merge($this->champsCvUpdateValides(), [
                'ville'       => 'Porto-Novo',
                'competences' => 'Gestion de projet, Agile',
                'experience'  => '5 ans',
                'formation'   => 'Master Management',
                'photo'       => null,
            ]))
            ->assertRedirect(route('candidat.cvs'));

        $this->assertDatabaseHas('cvs', [
            'id'    => $cv->id,
            'ville' => 'Porto-Novo',
        ]);
    }

    public function test_update_echoue_si_champs_obligatoires_manquants(): void
    {
        $candidat = $this->creerCandidat();
        $cv       = $this->creerCV($candidat, ['photo' => 'cvs/photos/existante.jpg']);

        $this->actingAs($candidat)
            ->put(route('candidat.cvs.update', $cv), ['ville' => 'Porto-Novo'])
            ->assertSessionHasErrors(['metier', 'resume', 'types_contrat_ids', 'niveau_etude_id', 'niveau_experience_id', 'competences', 'experience', 'formation', 'langues_ids']);
    }

    public function test_update_remplace_fichier_et_supprime_lancien(): void
    {
        Storage::fake('public');
        $candidat = $this->creerCandidat();
        Storage::disk('public')->put('cvs/ancien.pdf', 'contenu');
        $cv = $this->creerCV($candidat, ['fichier_path' => 'cvs/ancien.pdf', 'photo' => 'cvs/photos/existante.jpg']);

        $this->actingAs($candidat)
            ->put(route('candidat.cvs.update', $cv), array_merge($this->champsCvUpdateValides(), [
                'photo'        => null,
                'fichier_path' => UploadedFile::fake()->create('nouveau.pdf', 400, 'application/pdf'),
            ]))
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

    // ── Visibilité ──────────────────────────────────────────

    /**
     * Attributs scalaires qui rendent un CV "complet" au sens de CV::estComplet()
     * (mêmes champs que ceux obligatoires au dépôt).
     */
    private function attrsCvComplet(): array
    {
        return [
            'metier'            => 'Comptable',
            'ville'             => 'Cotonou',
            'resume'            => 'Résumé.',
            'competences'       => 'PHP, Laravel',
            'experience'        => 'Expérience.',
            'formation'         => 'Formation.',
            'langues'           => 'Français (Courant)',
            'niveau_etude'      => 'Licence',
            'niveau_experience' => '1-3 ans',
            'type_contrat'      => 'CDI',
            'photo'             => 'cvs/photos/existante.jpg',
            'fichier_path'      => 'cvs/existant.pdf',
        ];
    }

    public function test_toggle_visibilite_renseigne_publie_le_si_absent(): void
    {
        $candidat = $this->creerCandidat();
        $cv       = $this->creerCV($candidat, array_merge($this->attrsCvComplet(), ['visible' => false, 'publie_le' => null]));

        $this->actingAs($candidat)->patch(route('candidat.cvs.visibilite', $cv));

        $cv->refresh();
        $this->assertTrue($cv->visible);
        $this->assertNotNull($cv->publie_le);
    }

    public function test_toggle_visibilite_refuse_un_cv_incomplet(): void
    {
        $candidat = $this->creerCandidat();
        $cv       = $this->creerCV($candidat, ['visible' => false, 'publie_le' => null]);

        $this->actingAs($candidat)
            ->patch(route('candidat.cvs.visibilite', $cv))
            ->assertSessionHas('error');

        $cv->refresh();
        $this->assertFalse($cv->visible);
        $this->assertNull($cv->publie_le);
    }

    public function test_toggle_visibilite_ne_touche_pas_publie_le_deja_rempli(): void
    {
        $candidat  = $this->creerCandidat();
        $ancienneDate = now()->subMonth();
        $cv        = $this->creerCV($candidat, ['visible' => true, 'publie_le' => $ancienneDate]);

        $this->actingAs($candidat)->patch(route('candidat.cvs.visibilite', $cv));

        $cv->refresh();
        $this->assertFalse($cv->visible);
        $this->assertEquals($ancienneDate->timestamp, $cv->publie_le->timestamp);
    }

    // ── Sécurité : ancienne route publique de documents ────

    public function test_route_document_public_detail_nexiste_plus(): void
    {
        // La route publique /documents/{document} exposait n'importe quel document
        // d'un candidat sans aucune authentification ni contrôle de visibilité.
        // Elle n'était plus liée nulle part dans l'UI (dernier lien mort dans
        // _theque_liste.blade.php) — supprimée plutôt que gated, conformément
        // au principe que Document n'a aucun mécanisme de consentement public.
        $this->assertFalse(\Illuminate\Support\Facades\Route::has('document.public.detail'));
    }

    // ── Suppression ───────────────────────────────────────

    public function test_destroy_supprime_le_cv_du_proprietaire(): void
    {
        $candidat = $this->creerCandidat();
        $cv       = $this->creerCV($candidat);

        $this->actingAs($candidat)
            ->delete(route('candidat.cvs.destroy', $cv))
            ->assertRedirect(route('candidat.cvs'));

        // Suppression douce : la ligne reste en base (traçabilité pour les
        // candidatures/achats déjà liés à ce CV) mais n'apparaît plus dans
        // les requêtes normales.
        $this->assertSoftDeleted('cvs', ['id' => $cv->id]);
        $this->assertNull(\App\Models\CV::find($cv->id));
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
