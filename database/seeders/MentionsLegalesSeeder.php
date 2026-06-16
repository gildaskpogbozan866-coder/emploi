<?php

namespace Database\Seeders;

use App\Models\PageLegale;
use Illuminate\Database\Seeder;

class MentionsLegalesSeeder extends Seeder
{
    public function run(): void
    {
        PageLegale::updateOrCreate(
            ['slug' => 'mentions-legales'],
            [
                'titre'   => 'Mentions légales',
                'contenu' => $this->contenu(),
            ]
        );

        $this->command->info('✅ Mentions légales mises à jour.');
    }

    private function contenu(): string
    {
        return <<<'HTML'
<h2>Emploi Bouge Bénin</h2>
<p>Plateforme de recrutement et de mise en relation professionnelle</p>

<h2>1. Éditeur du site</h2>
<p><strong>Nom de la plateforme :</strong> Emploi Bouge Bénin</p>
<p><strong>Nature juridique :</strong> Entreprise individuelle</p>
<p><strong>Responsable de la publication :</strong> Gildas Hyacinthe KPOGBOZAN</p>
<p><strong>Adresse :</strong> Cotonou, République du Bénin</p>
<p><strong>Email :</strong> <a href="mailto:Gildaskpogbozan866@gmail.com">Gildaskpogbozan866@gmail.com</a></p>
<p><strong>Téléphone :</strong> +229 01 51 92 98 56</p>
<p><strong>Numéro IFU :</strong> 0202426980183</p>

<h2>2. Hébergement</h2>
<p><strong>Hébergeur :</strong> [Nom de l'hébergeur]</p>
<p><strong>Adresse :</strong> [Adresse de l'hébergeur]</p>
<p><strong>Site web :</strong> [URL de l'hébergeur]</p>

<h2>3. Objet de la plateforme</h2>
<p>Emploi Bouge Bénin est une plateforme numérique de recrutement permettant la mise en relation entre candidats à la recherche d'emploi, recruteurs, entreprises et prestataires de services professionnels au Bénin et dans les pays francophones d'Afrique.</p>
<p>La plateforme permet notamment :</p>
<ul>
  <li>La publication et la consultation d'offres d'emploi ;</li>
  <li>La mise en relation entre candidats et recruteurs ;</li>
  <li>La diffusion d'annonces professionnelles ;</li>
  <li>La promotion de services professionnels ;</li>
  <li>La gestion d'abonnements et de services premium liés à l'emploi et au recrutement.</li>
</ul>

<h2>4. Propriété intellectuelle</h2>
<p>L'ensemble des contenus présents sur la plateforme Emploi Bouge Bénin (textes, logos, graphismes, images, vidéos, bases de données, interface utilisateur, code source et autres éléments) est protégé par les lois relatives à la propriété intellectuelle.</p>
<p>Toute reproduction, représentation, modification, diffusion ou exploitation, totale ou partielle, sans autorisation écrite préalable de l'éditeur est strictement interdite.</p>

<h2>5. Limitation de responsabilité</h2>
<p>Emploi Bouge Bénin s'efforce de fournir des informations exactes et régulièrement mises à jour. Toutefois, la plateforme ne garantit ni l'exactitude, ni l'exhaustivité, ni l'actualité permanente des informations publiées.</p>
<p>Les utilisateurs demeurent seuls responsables des contenus qu'ils publient, notamment les offres d'emploi, CV, profils, annonces et commentaires.</p>
<p>Emploi Bouge Bénin ne saurait être tenu responsable :</p>
<ul>
  <li>Des informations fournies par les utilisateurs ;</li>
  <li>Des décisions de recrutement prises par les entreprises ;</li>
  <li>De l'issue des candidatures ;</li>
  <li>D'éventuels dommages résultant de l'utilisation de la plateforme.</li>
</ul>

<h2>6. Protection des données personnelles</h2>
<p>La collecte et le traitement des données personnelles sont réalisés conformément à la réglementation applicable en République du Bénin en matière de protection des données à caractère personnel.</p>
<p>Les modalités de traitement des données sont détaillées dans la <a href="/legale/politique-confidentialite">Politique de confidentialité</a> de la plateforme.</p>

<h2>7. Droit applicable et juridiction compétente</h2>
<p>Les présentes mentions légales sont soumises au droit béninois.</p>
<p>Tout litige relatif à l'utilisation de la plateforme Emploi Bouge Bénin sera soumis à la compétence exclusive des tribunaux de Cotonou, République du Bénin.</p>

<hr>

<h2>DESCRIPTIF DE TRAITEMENT DES DONNÉES PERSONNELLES</h2>
<h3>Déclaration auprès de l'APDP Bénin</h3>

<h3>Informations sur le responsable du traitement</h3>
<table>
  <thead>
    <tr><th>Champ</th><th>Information</th></tr>
  </thead>
  <tbody>
    <tr><td>Nom et prénom</td><td>Gildas Hyacinthe KPOGBOZAN</td></tr>
    <tr><td>Qualité</td><td>Responsable de la plateforme Emploi Bouge Bénin</td></tr>
    <tr><td>Adresse</td><td>Cotonou, République du Bénin</td></tr>
    <tr><td>Email</td><td><a href="mailto:Gildaskpogbozan866@gmail.com">Gildaskpogbozan866@gmail.com</a></td></tr>
    <tr><td>Téléphone</td><td>+229 01 51 92 98 56</td></tr>
    <tr><td>Numéro IFU</td><td>0202426980183</td></tr>
  </tbody>
</table>

<h3>Identification du traitement</h3>
<table>
  <thead>
    <tr><th>Champ</th><th>Information</th></tr>
  </thead>
  <tbody>
    <tr><td>Nom du traitement</td><td>Gestion des données utilisateurs de la plateforme Emploi Bouge Bénin</td></tr>
    <tr><td>Nature de l'opération</td><td>Collecte, enregistrement, conservation, consultation, mise en relation et transmission de données</td></tr>
    <tr><td>Type de déclaration</td><td>Déclaration ordinaire</td></tr>
  </tbody>
</table>

<h3>Finalités du traitement</h3>
<p>Le traitement des données a pour finalités :</p>
<ol>
  <li>La création et la gestion des comptes utilisateurs ;</li>
  <li>La mise en relation entre candidats et recruteurs ;</li>
  <li>La publication et la consultation d'offres d'emploi ;</li>
  <li>La gestion des candidatures ;</li>
  <li>L'envoi de notifications et communications relatives à la plateforme ;</li>
  <li>Le traitement des paiements et abonnements ;</li>
  <li>La gestion du support client ;</li>
  <li>L'amélioration continue des services proposés ;</li>
  <li>La prévention de la fraude et des abus.</li>
</ol>

<h3>Catégories de données traitées</h3>
<h4>Données des candidats</h4>
<ul>
  <li>Nom et prénom ;</li>
  <li>Adresse e-mail ;</li>
  <li>Numéro de téléphone ;</li>
  <li>Photo de profil (facultative) ;</li>
  <li>CV ;</li>
  <li>Formations ;</li>
  <li>Expériences professionnelles ;</li>
  <li>Compétences ;</li>
  <li>Informations de connexion et d'activité.</li>
</ul>

<h4>Données des recruteurs et entreprises</h4>
<ul>
  <li>Nom et prénom du responsable ;</li>
  <li>Raison sociale ;</li>
  <li>Secteur d'activité ;</li>
  <li>Adresse e-mail professionnelle ;</li>
  <li>Numéro de téléphone ;</li>
  <li>Informations relatives aux offres publiées ;</li>
  <li>Informations de paiement traitées par un prestataire sécurisé.</li>
</ul>

<h3>Personnes concernées</h3>
<ul>
  <li>Candidats inscrits sur la plateforme ;</li>
  <li>Recruteurs ;</li>
  <li>Entreprises ;</li>
  <li>Prestataires de services utilisant la plateforme.</li>
</ul>

<h3>Destinataires des données</h3>
<table>
  <thead>
    <tr><th>Destinataire</th><th>Données transmises</th><th>Motif</th></tr>
  </thead>
  <tbody>
    <tr><td>Recruteurs inscrits</td><td>Profils et CV des candidats</td><td>Recrutement</td></tr>
    <tr><td>Candidats inscrits</td><td>Informations sur les offres d'emploi</td><td>Candidature</td></tr>
    <tr><td>Hébergeur du site</td><td>Données techniques</td><td>Hébergement</td></tr>
    <tr><td>Prestataire de paiement</td><td>Données nécessaires aux transactions</td><td>Paiement</td></tr>
    <tr><td>Autorités compétentes</td><td>Selon les obligations légales</td><td>Conformité légale</td></tr>
  </tbody>
</table>

<h3>Durée de conservation</h3>
<table>
  <thead>
    <tr><th>Catégorie</th><th>Durée</th></tr>
  </thead>
  <tbody>
    <tr><td>Comptes actifs</td><td>Pendant toute la durée d'utilisation</td></tr>
    <tr><td>Comptes supprimés</td><td>3 mois après suppression</td></tr>
    <tr><td>Données de paiement</td><td>5 ans</td></tr>
    <tr><td>Journaux de connexion (logs)</td><td>12 mois</td></tr>
  </tbody>
</table>

<h3>Mesures de sécurité</h3>
<ul>
  <li>Chiffrement des mots de passe ;</li>
  <li>Utilisation du protocole HTTPS ;</li>
  <li>Contrôle des accès selon les rôles utilisateurs ;</li>
  <li>Sauvegardes régulières des données ;</li>
  <li>Surveillance des accès non autorisés ;</li>
  <li>Prestataires techniques respectant les standards de sécurité applicables.</li>
</ul>

<h3>Transfert des données hors du Bénin</h3>
<p>Selon le prestataire d'hébergement utilisé, certaines données peuvent être hébergées sur des serveurs situés hors du territoire béninois.</p>
<p>Dans ce cas, des mesures contractuelles et techniques appropriées sont mises en œuvre afin de garantir un niveau de protection adéquat des données personnelles.</p>

<h3>Droits des utilisateurs</h3>
<p>Chaque utilisateur dispose :</p>
<ul>
  <li>D'un droit d'accès à ses données ;</li>
  <li>D'un droit de rectification ;</li>
  <li>D'un droit de suppression ;</li>
  <li>D'un droit d'opposition au traitement ;</li>
  <li>D'un droit de retrait du consentement lorsque celui-ci est requis.</li>
</ul>
<p>Toute demande peut être adressée à :</p>
<p><strong>Email :</strong> <a href="mailto:Gildaskpogbozan866@gmail.com">Gildaskpogbozan866@gmail.com</a></p>
<p><strong>Téléphone :</strong> +229 01 51 92 98 56</p>
HTML;
    }
}
