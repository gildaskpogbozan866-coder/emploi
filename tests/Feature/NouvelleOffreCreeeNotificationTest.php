<?php

namespace Tests\Feature;

use App\Models\Offre;
use App\Models\RecruteurVerification;
use App\Models\TypeContrat;
use App\Models\User;
use App\Notifications\NouvelleOffreCreee;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class NouvelleOffreCreeeNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        Cache::flush();
    }

    public function test_le_mail_naffiche_pas_le_json_brut_du_type_contrat(): void
    {
        $recruteur = User::factory()->recruteur()->create();
        $recruteur->assignRole('recruteur');
        RecruteurVerification::create(['user_id' => $recruteur->id, 'statut' => 'approuve']);

        $typeContrat = TypeContrat::create(['code' => 'CDD', 'libelle' => 'CDD : Contrat à Durée Déterminée']);
        $offre = Offre::factory()->create([
            'recruteur_id'    => $recruteur->id,
            'type_contrat_id' => $typeContrat->id,
        ]);

        $mail = (new NouvelleOffreCreee($offre))->toMail($recruteur);
        $rendered = implode(' ', $mail->introLines);

        $this->assertStringNotContainsString('{"id"', $rendered);
        $this->assertStringContainsString('CDD : Contrat à Durée Déterminée', $rendered);
    }
}
