<?php

namespace Tests\Feature;

use App\Events\PaymentConfirmed;
use App\Models\Commande;
use App\Models\Paiement;
use App\Models\Service;
use App\Models\User;
use App\Notifications\NouvelleCommandeServiceNotification;
use App\Notifications\PaiementConfirmeNotification;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * Vérifie la règle métier : les emails de commande de service ne partent
 * jamais à la création ou à l'annulation, uniquement quand le paiement est
 * réellement confirmé (événement PaymentConfirmed).
 */
class ServiceCommandeEmailFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        Cache::flush();
    }

    private function creerClient(): User
    {
        $user = User::factory()->candidat()->create();
        $user->assignRole('candidat');
        return $user;
    }

    private function creerAdmin(): User
    {
        $user = User::factory()->create(['role' => 'admin']);
        $user->assignRole('admin');
        return $user;
    }

    private function creerService(): Service
    {
        return Service::create([
            'nom'         => 'CV Professionnel',
            'slug'        => 'cv-professionnel-'.uniqid(),
            'description' => 'Rédaction de CV par un expert',
            'prix'        => 15000,
            'devise'      => 'XOF',
            'delai'       => '48h',
            'type'        => 'cv',
            'actif'       => true,
        ]);
    }

    public function test_page_commande_accessible_connecte(): void
    {
        $client  = $this->creerClient();
        $service = $this->creerService();

        $this->actingAs($client)
            ->get(route('service.commande', $service))
            ->assertOk()
            ->assertSee('Vos informations');
    }

    public function test_page_commande_accessible_invite(): void
    {
        $service = $this->creerService();

        $this->get(route('service.commande', $service))
            ->assertOk()
            ->assertSee('Vos informations');
    }

    public function test_commander_un_service_ne_declenche_aucun_email(): void
    {
        Notification::fake();
        $client  = $this->creerClient();
        $service = $this->creerService();

        $this->actingAs($client)
            ->post(route('service.commande.store', $service), [
                'details_demande' => 'Merci de refaire mon CV',
            ])
            ->assertRedirect();

        $commande = Commande::first();
        $this->assertNotNull($commande);
        $this->assertSame('en_attente', $commande->statut);
        $this->assertSame('non_paye', $commande->paiement_statut);

        $paiement = Paiement::first();
        $this->assertNotNull($paiement);
        $this->assertSame('en_attente', $paiement->statut);

        Notification::assertNothingSent();
    }

    public function test_commander_connecte_recupere_prenom_nom_tel_depuis_le_compte(): void
    {
        Notification::fake();
        $client  = $this->creerClient();
        $client->update(['prenom' => 'Aïchatou', 'nom' => 'Dossou', 'tel' => '+229 97 00 00 00']);
        $service = $this->creerService();

        // Un client connecté ne renvoie rien pour ces champs (readonly côté vue) —
        // le serveur doit ignorer toute valeur soumise et faire confiance au compte.
        $this->actingAs($client)
            ->post(route('service.commande.store', $service), [
                'prenom'    => 'Faux Prénom',
                'nom'       => 'Faux Nom',
                'telephone' => '+229 00 00 00 00',
            ])
            ->assertRedirect();

        $commande = Commande::first();
        $this->assertSame('Aïchatou', $commande->prenom);
        $this->assertSame('Dossou', $commande->nom);
        $this->assertSame('+229 97 00 00 00', $commande->telephone);
    }

    public function test_commander_invite_sans_infos_echoue(): void
    {
        Notification::fake();
        $service = $this->creerService();

        $this->post(route('service.commande.store', $service), [
            'email_contact' => 'invite@gmail.com',
        ])
            ->assertSessionHasErrors(['prenom', 'nom', 'telephone']);

        $this->assertDatabaseCount('commandes', 0);
    }

    public function test_commander_invite_avec_infos_completes_reussit(): void
    {
        Notification::fake();
        $service = $this->creerService();

        $this->post(route('service.commande.store', $service), [
            'email_contact' => 'invite@gmail.com',
            'prenom'        => 'Rodrigue',
            'nom'           => 'Kpogbozan',
            'telephone'     => '+229 96 00 00 00',
        ])
            ->assertRedirect();

        $commande = Commande::first();
        $this->assertNotNull($commande);
        $this->assertNull($commande->user_id);
        $this->assertSame('invite@gmail.com', $commande->email_contact);
        $this->assertSame('Rodrigue', $commande->prenom);
        $this->assertSame('Kpogbozan', $commande->nom);
        $this->assertSame('+229 96 00 00 00', $commande->telephone);
    }

    public function test_annuler_une_commande_ne_declenche_aucun_email(): void
    {
        Notification::fake();
        $admin   = $this->creerAdmin();
        $client  = $this->creerClient();
        $service = $this->creerService();

        $commande = Commande::create([
            'user_id'         => $client->id,
            'service_id'      => $service->id,
            'montant'         => $service->prix,
            'statut'          => 'en_attente',
            'paiement_statut' => 'non_paye',
            'email_contact'   => $client->email,
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.commandes.statut', $commande), [
                'statut' => 'annulee',
            ])
            ->assertRedirect();

        $this->assertSame('annulee', $commande->fresh()->statut);

        Notification::assertNothingSent();
    }

    public function test_paiement_refuse_ne_declenche_aucun_email(): void
    {
        Notification::fake();
        $client  = $this->creerClient();
        $service = $this->creerService();

        $commande = Commande::create([
            'user_id'         => $client->id,
            'service_id'      => $service->id,
            'montant'         => $service->prix,
            'statut'          => 'en_attente',
            'paiement_statut' => 'non_paye',
            'email_contact'   => $client->email,
        ]);

        $paiement = Paiement::create([
            'user_id'      => $client->id,
            'montant'      => $service->prix,
            'devise'       => 'XOF',
            'type'         => 'service',
            'statut'       => 'en_attente',
            'payable_id'   => $commande->id,
            'payable_type' => Commande::class,
        ]);

        // Simule un webhook FedaPay "declined" : le contrôleur fait juste
        // $paiement->update(['statut' => 'echec']) sans jamais dispatcher
        // PaymentConfirmed ni notifier personne.
        $paiement->update(['statut' => 'echec']);

        Notification::assertNothingSent();
    }

    public function test_paiement_confirme_declenche_les_bons_emails(): void
    {
        Notification::fake();
        $admin   = $this->creerAdmin();
        $client  = $this->creerClient();
        $service = $this->creerService();

        $commande = Commande::create([
            'user_id'         => $client->id,
            'service_id'      => $service->id,
            'montant'         => $service->prix,
            'statut'          => 'en_attente',
            'paiement_statut' => 'non_paye',
            'email_contact'   => $client->email,
        ]);

        $paiement = Paiement::create([
            'user_id'      => $client->id,
            'montant'      => $service->prix,
            'devise'       => 'XOF',
            'type'         => 'service',
            'statut'       => 'en_attente',
            'payable_id'   => $commande->id,
            'payable_type' => Commande::class,
        ]);

        // Simule exactement ce que fait le webhook FedaPay une fois le
        // paiement réellement approuvé côté gateway.
        $paiement->update(['statut' => 'confirme']);
        event(new PaymentConfirmed($paiement));

        $commande->refresh();
        $this->assertSame('paye', $commande->paiement_statut);
        $this->assertSame('en_cours', $commande->statut);

        Notification::assertSentTo($client, PaiementConfirmeNotification::class);
        Notification::assertSentTo($admin, NouvelleCommandeServiceNotification::class);
    }
}
