<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\RecruteurVerification;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * Une conversation archivée n'avait aucun moyen d'être consultée ni restaurée
 * manuellement — seul un nouveau message de l'autre participant la désarchivait
 * automatiquement. Ajout d'une page "Archives" + bouton "Restaurer".
 */
class MessagerieArchivesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        Cache::flush();
    }

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

    private function creerConversation(User $a, User $b, array $attrs = []): Conversation
    {
        [$u1, $u2] = [min($a->id, $b->id), max($a->id, $b->id)];

        return Conversation::create(array_merge([
            'user1_id' => $u1,
            'user2_id' => $u2,
            'dernier_message_at' => now(),
        ], $attrs));
    }

    public function test_page_archives_liste_les_conversations_archivees(): void
    {
        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $conv  = $this->creerConversation($candidat, $recruteur);
        $champ = $conv->user1_id === $candidat->id ? 'archived_by_user1' : 'archived_by_user2';
        $conv->update([$champ => true]);

        $this->actingAs($candidat)
            ->get(route('candidat.messagerie.archives'))
            ->assertOk()
            ->assertSee($recruteur->nom_complet);

        // Elle ne doit plus apparaître dans la liste principale (non archivées).
        $this->actingAs($candidat)
            ->get(route('candidat.messagerie'))
            ->assertDontSee($recruteur->nom_complet);
    }

    public function test_restaurer_remet_la_conversation_dans_la_liste_principale(): void
    {
        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $conv = $this->creerConversation($candidat, $recruteur);
        $champ = $conv->user1_id === $candidat->id ? 'archived_by_user1' : 'archived_by_user2';
        $conv->update([$champ => true]);

        $this->actingAs($candidat)
            ->post(route('candidat.messagerie.restaurer', $conv))
            ->assertRedirect(route('candidat.messagerie.archives'));

        $this->assertFalse($conv->fresh()->isArchivedFor($candidat->id));

        $this->actingAs($candidat)
            ->get(route('candidat.messagerie'))
            ->assertSee($recruteur->nom_complet);
    }

    public function test_restaurer_interdit_a_un_participant_etranger(): void
    {
        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $intrus    = $this->creerCandidat();
        $conv = $this->creerConversation($candidat, $recruteur);

        $this->actingAs($intrus)
            ->post(route('candidat.messagerie.restaurer', $conv))
            ->assertForbidden();
    }

    public function test_archivage_reste_prive_a_chaque_participant(): void
    {
        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $conv = $this->creerConversation($candidat, $recruteur);

        // Le candidat archive de son côté.
        $this->actingAs($candidat)->post(route('candidat.messagerie.archiver', $conv));

        // Le recruteur continue de la voir normalement, elle n'apparaît pas
        // dans ses propres archives.
        $this->actingAs($recruteur)
            ->get(route('recruteur.messagerie'))
            ->assertSee($candidat->nom_complet);

        $this->actingAs($recruteur)
            ->get(route('recruteur.messagerie.archives'))
            ->assertDontSee($candidat->nom_complet);
    }
}
