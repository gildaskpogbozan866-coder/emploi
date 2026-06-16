<?php

namespace Database\Seeders;

use App\Models\PageLegale;
use Illuminate\Database\Seeder;

class PageLegaleSeeder extends Seeder
{
    public function run(): void
    {
        PageLegale::updateOrCreate(
            ['slug' => 'politique-confidentialite'],
            [
                'titre'   => 'Politique de confidentialité',
                'contenu' => $this->contenu(),
            ]
        );

        $this->command->info('✅ Politique de confidentialité mise à jour.');
    }

    private function contenu(): string
    {
        return <<<'HTML'
<h2>1. Présentation</h2>
<p>Emploi Bouge Bénin est une plateforme numérique de recrutement et de mise en relation entre candidats, recruteurs, entreprises et prestataires de services professionnels, opérée depuis le Bénin.</p>
<p>Dans le cadre de nos services, nous sommes amenés à collecter et traiter des données à caractère personnel conformément à la législation béninoise en vigueur relative à la protection des données personnelles.</p>
<p>La présente politique décrit les types de données collectées, les finalités de leur traitement, les droits des utilisateurs ainsi que les mesures de sécurité mises en œuvre pour protéger leurs informations.</p>

<h2>2. Responsable du traitement</h2>
<p><strong>Nom de la plateforme :</strong> Emploi Bouge Bénin</p>
<p><strong>Responsable de traitement :</strong> Gildas Hyacinthe KPOGBOZAN</p>
<p><strong>Adresse :</strong> Cotonou, République du Bénin</p>
<p><strong>Email :</strong> <a href="mailto:Gildaskpogbozan866@gmail.com">Gildaskpogbozan866@gmail.com</a></p>
<p><strong>Téléphone :</strong> +229 01 51 92 98 56</p>
<p><strong>Numéro IFU :</strong> 0202426980183</p>

<h2>3. Données collectées</h2>
<p>Selon votre profil et votre utilisation de la plateforme, nous pouvons collecter les données suivantes :</p>

<h3>Pour les candidats</h3>
<ul>
  <li>Nom et prénom</li>
  <li>Adresse e-mail</li>
  <li>Numéro de téléphone</li>
  <li>CV</li>
  <li>Formation académique</li>
  <li>Expériences professionnelles</li>
  <li>Compétences professionnelles</li>
  <li>Lettre de motivation (si fournie)</li>
  <li>Photo de profil (facultative)</li>
</ul>

<h3>Pour les recruteurs et entreprises</h3>
<ul>
  <li>Nom et prénom du responsable</li>
  <li>Nom de l'entreprise</li>
  <li>Adresse e-mail professionnelle</li>
  <li>Numéro de téléphone</li>
  <li>Secteur d'activité</li>
  <li>Informations relatives aux offres d'emploi publiées</li>
  <li>Informations nécessaires à la facturation et aux abonnements</li>
</ul>

<h3>Données techniques</h3>
<ul>
  <li>Adresse IP</li>
  <li>Type d'appareil et navigateur</li>
  <li>Données de connexion</li>
  <li>Historique d'activité sur la plateforme</li>
  <li>Cookies techniques nécessaires au fonctionnement du service</li>
</ul>

<h2>4. Finalités du traitement</h2>
<p>Vos données sont collectées et utilisées pour :</p>
<ul>
  <li>Créer et gérer votre compte utilisateur ;</li>
  <li>Permettre la mise en relation entre candidats et recruteurs ;</li>
  <li>Publier et consulter des offres d'emploi ;</li>
  <li>Gérer les candidatures ;</li>
  <li>Envoyer des notifications et communications liées à votre activité ;</li>
  <li>Traiter les paiements, abonnements et services premium ;</li>
  <li>Fournir une assistance aux utilisateurs ;</li>
  <li>Améliorer la qualité des services proposés ;</li>
  <li>Prévenir les fraudes, abus et utilisations illicites ;</li>
  <li>Respecter les obligations légales applicables.</li>
</ul>

<h2>5. Base légale du traitement</h2>
<p>Le traitement de vos données repose sur :</p>
<ul>
  <li>Votre consentement lors de votre inscription ;</li>
  <li>L'exécution des services proposés par Emploi Bouge Bénin ;</li>
  <li>Le respect de nos obligations légales et réglementaires ;</li>
  <li>Notre intérêt légitime à assurer le bon fonctionnement et la sécurité de la plateforme.</li>
</ul>

<h2>6. Durée de conservation</h2>
<table>
  <thead>
    <tr>
      <th>Type de données</th>
      <th>Durée de conservation</th>
    </tr>
  </thead>
  <tbody>
    <tr><td>Compte utilisateur actif</td><td>Pendant toute la durée d'utilisation</td></tr>
    <tr><td>Compte supprimé</td><td>Maximum 3 mois après suppression</td></tr>
    <tr><td>Données de paiement et facturation</td><td>5 ans</td></tr>
    <tr><td>CV et documents professionnels</td><td>Durée d'utilisation + 3 mois</td></tr>
    <tr><td>Journaux de connexion (logs)</td><td>12 mois</td></tr>
  </tbody>
</table>

<h2>7. Partage des données</h2>
<p>Emploi Bouge Bénin ne vend jamais les données personnelles de ses utilisateurs.</p>
<p>Les données peuvent être partagées uniquement dans les cas suivants :</p>
<ul>
  <li>Entre candidats et recruteurs dans le cadre du processus de recrutement ;</li>
  <li>Avec les prestataires techniques chargés de l'hébergement, des e-mails ou du paiement ;</li>
  <li>Avec les autorités administratives ou judiciaires compétentes lorsque la loi l'exige.</li>
</ul>
<p>Tous les partenaires intervenant dans le traitement des données sont soumis à des obligations strictes de confidentialité.</p>

<h2>8. Sécurité des données</h2>
<p>Nous mettons en œuvre des mesures techniques et organisationnelles appropriées afin de protéger les données personnelles :</p>
<ul>
  <li>Chiffrement sécurisé des mots de passe ;</li>
  <li>Utilisation du protocole HTTPS ;</li>
  <li>Accès limité aux données selon les rôles utilisateurs ;</li>
  <li>Sauvegardes régulières et sécurisées ;</li>
  <li>Surveillance et protection contre les accès non autorisés ;</li>
  <li>Prestataires techniques respectant les normes de sécurité applicables.</li>
</ul>

<h2>9. Vos droits</h2>
<p>Conformément à la réglementation applicable, vous disposez des droits suivants :</p>
<ul>
  <li>Droit d'accès à vos données ;</li>
  <li>Droit de rectification ;</li>
  <li>Droit de suppression ;</li>
  <li>Droit d'opposition ;</li>
  <li>Droit à la limitation du traitement ;</li>
  <li>Droit à la portabilité de vos données lorsque cela est applicable.</li>
</ul>
<p>Pour exercer vos droits, contactez-nous :</p>
<p><strong>Email :</strong> <a href="mailto:Gildaskpogbozan866@gmail.com">Gildaskpogbozan866@gmail.com</a></p>
<p><strong>Téléphone :</strong> +229 01 51 92 98 56</p>
<p>Vous pouvez également saisir l'Autorité de Protection des Données Personnelles (APDP) du Bénin en cas de réclamation.</p>

<h2>10. Cookies</h2>
<p>Emploi Bouge Bénin utilise des cookies techniques indispensables au fonctionnement de la plateforme.</p>
<p>Aucun cookie publicitaire ou de suivi marketing n'est déposé sans votre consentement préalable lorsque celui-ci est requis par la réglementation.</p>

<h2>11. Modification de la politique</h2>
<p>Nous pouvons modifier la présente Politique de confidentialité à tout moment afin de tenir compte des évolutions légales, techniques ou fonctionnelles de la plateforme.</p>
<p>Toute modification importante sera portée à la connaissance des utilisateurs par e-mail ou via une notification sur le site.</p>
<p>La date de dernière mise à jour figure en haut du présent document.</p>

<h2>12. Contact</h2>
<p><strong>Emploi Bouge Bénin</strong></p>
<p><strong>Responsable :</strong> Gildas Hyacinthe KPOGBOZAN</p>
<p><strong>Email :</strong> <a href="mailto:Gildaskpogbozan866@gmail.com">Gildaskpogbozan866@gmail.com</a></p>
<p><strong>Téléphone :</strong> +229 01 51 92 98 56</p>
<p><strong>Adresse :</strong> Cotonou, République du Bénin</p>
HTML;
    }
}
