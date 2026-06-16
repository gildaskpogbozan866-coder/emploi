<?php

namespace Database\Seeders;

use App\Models\PageLegale;
use Illuminate\Database\Seeder;

class CGVSeeder extends Seeder
{
    public function run(): void
    {
        PageLegale::updateOrCreate(
            ['slug' => 'conditions-generales-de-vente'],
            [
                'titre'   => 'Conditions Générales de Vente',
                'contenu' => $this->contenu(),
            ]
        );

        $this->command->info('✅ Conditions Générales de Vente mises à jour.');
    }

    private function contenu(): string
    {
        return <<<'HTML'
<h2>Emploi Bouge Bénin</h2>

<h2>1. Objet</h2>
<p>Les présentes CGV encadrent la vente des services payants proposés sur la plateforme Emploi Bouge Bénin.</p>

<h2>2. Services concernés</h2>
<p>Les services payants peuvent inclure :</p>
<ul>
  <li>Abonnements candidats ;</li>
  <li>Abonnements recruteurs ;</li>
  <li>Publication d'offres premium ;</li>
  <li>Mise en avant d'offres d'emploi ;</li>
  <li>Publicités et bannières ;</li>
  <li>Services professionnels proposés sur la plateforme ;</li>
  <li>Toute autre prestation affichée sur le site.</li>
</ul>

<h2>3. Prix</h2>
<p>Les prix sont indiqués en Franc CFA (XOF).</p>
<p>Emploi Bouge Bénin se réserve le droit de modifier ses tarifs à tout moment.</p>
<p>Les nouveaux tarifs ne s'appliquent pas aux services déjà payés.</p>

<h2>4. Paiement</h2>
<p>Les paiements sont effectués via les moyens de paiement disponibles sur la plateforme.</p>
<p>La validation du paiement entraîne l'activation du service concerné.</p>

<h2>5. Absence de remboursement</h2>
<p>Sauf disposition légale contraire ou erreur imputable à Emploi Bouge Bénin, les paiements effectués ne sont pas remboursables une fois le service activé.</p>

<h2>6. Obligations du client</h2>
<p>Le client s'engage à fournir des informations exactes et à utiliser les services conformément aux lois en vigueur.</p>

<h2>7. Suspension du service</h2>
<p>Emploi Bouge Bénin peut suspendre un service ou un compte en cas de fraude, de non-respect des CGU ou d'utilisation abusive de la plateforme.</p>

<h2>8. Responsabilité</h2>
<p>La responsabilité d'Emploi Bouge Bénin est limitée au montant effectivement payé par l'utilisateur pour le service concerné.</p>

<h2>9. Force majeure</h2>
<p>Emploi Bouge Bénin ne pourra être tenu responsable en cas d'événement de force majeure empêchant l'exécution normale des services.</p>

<h2>10. Droit applicable</h2>
<p>Les présentes CGV sont soumises au droit béninois.</p>
<p>Tout litige relatif à leur exécution relève de la compétence des tribunaux de Cotonou.</p>
HTML;
    }
}
