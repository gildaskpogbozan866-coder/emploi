<?php

namespace Database\Seeders;

use App\Models\PageLegale;
use Illuminate\Database\Seeder;

class CGUSeeder extends Seeder
{
    public function run(): void
    {
        PageLegale::updateOrCreate(
            ['slug' => 'conditions-generales-utilisation'],
            [
                'titre'   => "Conditions Générales d'Utilisation",
                'contenu' => $this->contenu(),
            ]
        );

        $this->command->info("✅ Conditions Générales d'Utilisation mises à jour.");
    }

    private function contenu(): string
    {
        return <<<'HTML'
<h2>Emploi Bouge Bénin</h2>
<p>Plateforme de recrutement et de mise en relation professionnelle</p>

<h2>1. Présentation et acceptation</h2>
<p>Les présentes Conditions Générales d'Utilisation (CGU) régissent l'accès et l'utilisation de la plateforme Emploi Bouge Bénin, accessible en ligne, éditée par Gildas Hyacinthe KPOGBOZAN, entreprise individuelle domiciliée à Cotonou, République du Bénin.</p>
<p>Toute inscription ou utilisation de la plateforme vaut acceptation pleine et entière des présentes CGU. Si vous n'acceptez pas ces conditions, vous ne devez pas utiliser la plateforme.</p>

<h2>2. Description du service</h2>
<p>Emploi Bouge Bénin est une plateforme numérique permettant :</p>
<ul>
  <li>Aux <strong>candidats</strong> de créer un profil, déposer un CV, consulter et postuler à des offres d'emploi ;</li>
  <li>Aux <strong>recruteurs et entreprises</strong> de publier des offres d'emploi, consulter des CV et gérer des candidatures ;</li>
  <li>Aux <strong>talents et prestataires</strong> de proposer des services professionnels ;</li>
  <li>À toute personne intéressée de consulter les offres et contenus publics de la plateforme.</li>
</ul>

<h2>3. Inscription et compte utilisateur</h2>
<p>L'accès aux fonctionnalités principales de la plateforme nécessite la création d'un compte personnel.</p>
<p>Lors de l'inscription, l'utilisateur s'engage à :</p>
<ul>
  <li>Fournir des informations exactes, complètes et à jour ;</li>
  <li>Choisir un mot de passe sécurisé et confidentiel ;</li>
  <li>Ne pas usurper l'identité d'une tierce personne ou d'une entreprise ;</li>
  <li>Signaler immédiatement toute utilisation non autorisée de son compte.</li>
</ul>
<p>Chaque utilisateur est responsable de la confidentialité de ses identifiants de connexion. Emploi Bouge Bénin ne saurait être tenu responsable des conséquences d'une divulgation de ces informations à des tiers.</p>

<h2>4. Vérification des recruteurs</h2>
<p>Les comptes recruteurs font l'objet d'un processus de vérification avant activation complète. L'équipe Emploi Bouge Bénin se réserve le droit d'approuver ou de rejeter tout dossier de vérification, sans avoir à justifier sa décision.</p>
<p>Un recruteur dont le dossier n'est pas approuvé ne peut pas publier d'offres d'emploi sur la plateforme.</p>

<h2>5. Règles de publication</h2>
<p>Les utilisateurs s'engagent à publier uniquement des contenus :</p>
<ul>
  <li>Véridiques et non trompeurs ;</li>
  <li>Respectueux des lois et règlements en vigueur ;</li>
  <li>Exempts de contenus discriminatoires, offensants, illicites ou portant atteinte aux droits de tiers ;</li>
  <li>Conformes à l'objet professionnel de la plateforme.</li>
</ul>
<p>Sont notamment interdits :</p>
<ul>
  <li>Les offres fictives ou frauduleuses ;</li>
  <li>Les profils ou CV contenant de fausses informations ;</li>
  <li>Les contenus à caractère publicitaire non autorisés ;</li>
  <li>Les messages à caractère commercial non sollicités (spam).</li>
</ul>

<h2>6. Candidatures</h2>
<p>Les candidats sont seuls responsables des candidatures qu'ils soumettent et des informations contenues dans leurs CV et lettres de motivation.</p>
<p>Emploi Bouge Bénin n'intervient pas dans le processus de recrutement et n'est pas partie aux relations entre candidats et recruteurs.</p>

<h2>7. Services payants</h2>
<p>Certaines fonctionnalités de la plateforme sont soumises à un paiement préalable (abonnements, publications premium, publicités, etc.).</p>
<p>Les conditions financières applicables sont détaillées dans les <a href="/legale/conditions-generales-de-vente">Conditions Générales de Vente (CGV)</a>.</p>

<h2>8. Propriété intellectuelle</h2>
<p>L'ensemble des éléments constituant la plateforme (interface, textes, logos, images, base de données, code source) est la propriété exclusive d'Emploi Bouge Bénin et est protégé par le droit applicable.</p>
<p>Les utilisateurs conservent la propriété des contenus qu'ils publient (CV, offres, articles) mais concèdent à Emploi Bouge Bénin une licence non exclusive d'affichage et de diffusion sur la plateforme pour la durée de leur utilisation du service.</p>

<h2>9. Protection des données personnelles</h2>
<p>Le traitement des données personnelles des utilisateurs est effectué conformément à la <a href="/legale/politique-confidentialite">Politique de confidentialité</a> de la plateforme, qui fait partie intégrante des présentes CGU.</p>

<h2>10. Comportement prohibé</h2>
<p>Il est interdit à tout utilisateur de :</p>
<ul>
  <li>Tenter de contourner les mesures de sécurité de la plateforme ;</li>
  <li>Collecter des données d'autres utilisateurs sans leur consentement ;</li>
  <li>Utiliser des robots ou outils automatisés pour accéder à la plateforme sans autorisation ;</li>
  <li>Perturber le fonctionnement normal du service ;</li>
  <li>Créer plusieurs comptes dans un but frauduleux.</li>
</ul>

<h2>11. Suspension et résiliation de compte</h2>
<p>Emploi Bouge Bénin se réserve le droit de suspendre ou de supprimer tout compte en cas de :</p>
<ul>
  <li>Non-respect des présentes CGU ;</li>
  <li>Utilisation frauduleuse ou abusive de la plateforme ;</li>
  <li>Fourniture d'informations fausses lors de l'inscription ;</li>
  <li>Atteinte aux droits d'autres utilisateurs ou de tiers.</li>
</ul>
<p>L'utilisateur peut lui-même clôturer son compte à tout moment depuis son espace personnel.</p>

<h2>12. Limitation de responsabilité</h2>
<p>Emploi Bouge Bénin met tout en œuvre pour assurer la disponibilité et la qualité de la plateforme, mais ne garantit pas un accès ininterrompu au service.</p>
<p>La plateforme ne saurait être tenue responsable :</p>
<ul>
  <li>Des contenus publiés par les utilisateurs ;</li>
  <li>Des décisions de recrutement prises par les entreprises ;</li>
  <li>De l'issue des candidatures ;</li>
  <li>Des relations contractuelles entre utilisateurs ;</li>
  <li>Des dommages indirects résultant de l'utilisation ou de l'impossibilité d'utiliser la plateforme.</li>
</ul>

<h2>13. Liens externes</h2>
<p>La plateforme peut contenir des liens vers des sites tiers. Emploi Bouge Bénin n'exerce aucun contrôle sur ces sites et décline toute responsabilité quant à leur contenu.</p>

<h2>14. Modification des CGU</h2>
<p>Emploi Bouge Bénin se réserve le droit de modifier les présentes CGU à tout moment. Les utilisateurs seront informés de toute modification substantielle par e-mail ou notification sur la plateforme.</p>
<p>La poursuite de l'utilisation de la plateforme après modification des CGU vaut acceptation des nouvelles conditions.</p>

<h2>15. Droit applicable et juridiction</h2>
<p>Les présentes CGU sont soumises au droit de la République du Bénin.</p>
<p>Tout litige relatif à leur interprétation ou à leur exécution sera soumis à la compétence exclusive des tribunaux de Cotonou, République du Bénin.</p>

<h2>16. Contact</h2>
<p>Pour toute question relative aux présentes CGU :</p>
<p><strong>Email :</strong> <a href="mailto:Gildaskpogbozan866@gmail.com">Gildaskpogbozan866@gmail.com</a></p>
<p><strong>Téléphone :</strong> +229 01 51 92 98 56</p>
<p><strong>Adresse :</strong> Cotonou, République du Bénin</p>
HTML;
    }
}
