<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticlesSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@emploibougebenin.com')->first();

        if (! $admin) {
            $this->command->error('Compte admin introuvable. Lancez AdminSeeder d\'abord.');
            return;
        }

        $articles = [
            [
                'titre'        => 'Comment rédiger un CV qui attire les recruteurs au Bénin en 2026',
                'slug'         => 'rediger-cv-2026',
                'image'        => 'blog/article-cv.svg',
                'categorie'    => 'Conseils CV',
                'temps_lecture'=> 5,
                'extrait'      => 'Un CV bien rédigé est votre première carte de visite. Découvrez les erreurs à éviter et les astuces concrètes pour créer un CV percutant adapté au marché béninois.',
                'contenu'      => <<<HTML
<p>Au Bénin comme partout en Afrique de l'Ouest, le marché de l'emploi est compétitif. Pour se démarquer, votre CV doit être soigné, clair et adapté au poste visé. Voici un guide complet pour y parvenir.</p>

<h2>1. Soignez la mise en forme avant tout</h2>
<p>Un recruteur passe en moyenne <strong>30 secondes</strong> sur un CV avant de décider de le lire ou non. Votre document doit donc être immédiatement lisible :</p>
<ul>
  <li>Utilisez une police professionnelle (Calibri, Arial ou Helvetica, taille 10-11)</li>
  <li>Limitez votre CV à <strong>une page</strong> si vous avez moins de 5 ans d'expérience</li>
  <li>Laissez des espaces blancs pour aérer le contenu</li>
  <li>Évitez les couleurs criardes — restez sobre et professionnel</li>
</ul>

<h2>2. Adaptez votre CV à chaque offre</h2>
<p>Le même CV envoyé à toutes les offres ne fonctionne pas. Prenez 10 minutes pour personnaliser votre résumé de profil et vos compétences en fonction de la fiche de poste. Les mots-clés utilisés dans l'offre doivent apparaître dans votre CV.</p>

<h2>3. Mettez en avant vos réalisations, pas juste vos tâches</h2>
<p>Au lieu d'écrire <em>"Responsable de la comptabilité"</em>, préférez <em>"Gestion de la comptabilité pour un portefeuille de 50 clients, réduction des délais de clôture mensuelle de 3 jours"</em>. Les chiffres et résultats concrets font la différence.</p>

<h2>4. Les sections indispensables</h2>
<ul>
  <li><strong>Résumé de profil :</strong> 3-4 lignes qui résument qui vous êtes et ce que vous apportez</li>
  <li><strong>Expériences professionnelles :</strong> en ordre antéchronologique (la plus récente en premier)</li>
  <li><strong>Formation :</strong> diplômes, certifications, formations continues</li>
  <li><strong>Compétences :</strong> techniques ET comportementales (soft skills)</li>
  <li><strong>Langues :</strong> précisez le niveau (courant, intermédiaire, notions)</li>
</ul>

<h2>5. Les erreurs à éviter absolument</h2>
<ul>
  <li>Fautes d'orthographe (utilisez un correcteur avant d'envoyer)</li>
  <li>Photo non professionnelle ou absente quand elle est demandée</li>
  <li>Adresse email peu sérieuse (créez une adresse prénom.nom@gmail.com)</li>
  <li>Informations obsolètes ou non mises à jour</li>
  <li>CV au format Word — envoyez toujours un <strong>PDF</strong></li>
</ul>

<h2>Conclusion</h2>
<p>Votre CV est votre outil de vente numéro un. Investissez du temps pour le perfectionner. Si vous avez besoin d'aide, notre service de <strong>rédaction de CV professionnel</strong> est disponible sur Emploi Bouge Bénin pour vous accompagner.</p>
HTML,
            ],

            [
                'titre'        => 'Les 10 questions les plus fréquentes en entretien d\'embauche (et comment y répondre)',
                'slug'         => 'questions-entretien-frequentes',
                'image'        => 'blog/article-entretien.svg',
                'categorie'    => 'Entretien',
                'temps_lecture'=> 7,
                'extrait'      => 'Préparez-vous avec nos réponses types et stratégies pour convaincre chaque recruteur dès les premières minutes.',
                'contenu'      => <<<HTML
<p>L'entretien d'embauche est souvent la partie la plus stressante du processus de recrutement. Pourtant, avec une bonne préparation, vous pouvez aborder cette étape avec confiance. Voici les 10 questions incontournables et comment y répondre efficacement.</p>

<h2>1. "Parlez-moi de vous"</h2>
<p>C'est la question d'ouverture classique. Ne racontez pas votre vie — résumez votre parcours en <strong>2 minutes maximum</strong> : formation, expériences clés, et pourquoi vous postulez à ce poste. Suivez la structure : passé → présent → futur.</p>

<h2>2. "Pourquoi voulez-vous travailler chez nous ?"</h2>
<p>Cette question teste votre préparation. Renseignez-vous sur l'entreprise : ses valeurs, ses projets récents, sa réputation. Montrez que vous avez fait vos recherches et que ce poste correspond à un projet de carrière réfléchi, pas juste un besoin d'argent.</p>

<h2>3. "Quelles sont vos principales qualités ?"</h2>
<p>Citez 3 qualités en rapport avec le poste et illustrez chacune par un exemple concret. Évitez les clichés comme "je suis perfectionniste" sans illustration.</p>

<h2>4. "Quels sont vos défauts ?"</h2>
<p>Ne dites pas "j'ai aucun défaut". Choisissez un défaut réel mais montrez que vous en êtes conscient et que vous travaillez à l'améliorer. Exemple : <em>"J'avais du mal à déléguer, mais j'ai appris à faire confiance à mon équipe avec de meilleures méthodes de suivi."</em></p>

<h2>5. "Où vous voyez-vous dans 5 ans ?"</h2>
<p>Le recruteur cherche à voir si vous êtes ambitieux et si votre vision s'aligne avec les perspectives de l'entreprise. Répondez avec des objectifs réalistes, liés à votre développement professionnel dans le secteur.</p>

<h2>6. "Parlez-moi d'une situation difficile et comment vous l'avez gérée"</h2>
<p>Utilisez la méthode <strong>STAR</strong> : Situation, Tâche, Action, Résultat. Racontez un défi professionnel réel, ce que vous avez fait et le résultat obtenu.</p>

<h2>7. "Pourquoi quittez-vous votre emploi actuel ?"</h2>
<p>Ne critiquez jamais votre ancien employeur. Parlez plutôt de votre désir d'évolution, de nouveaux défis ou d'un alignement avec vos valeurs personnelles.</p>

<h2>8. "Quelles sont vos prétentions salariales ?"</h2>
<p>Renseignez-vous sur les salaires pratiqués dans le secteur. Donnez une fourchette réaliste en disant : <em>"En fonction des responsabilités et des avantages, j'envisage entre X et Y FCFA."</em></p>

<h2>9. "Savez-vous travailler en équipe ?"</h2>
<p>Ne dites pas simplement "oui". Donnez un exemple précis d'un projet mené collectivement, votre rôle dans l'équipe et le succès obtenu.</p>

<h2>10. "Avez-vous des questions pour nous ?"</h2>
<p>Toujours dire <strong>oui</strong>. Préparez 2-3 questions intelligentes sur le poste, l'équipe ou les défis de l'entreprise. Cela montre votre sérieux et votre intérêt genuine.</p>

<h2>Conseils finaux</h2>
<ul>
  <li>Arrivez 10 minutes en avance (ou connectez-vous 5 minutes avant pour un entretien en ligne)</li>
  <li>Habillez-vous de façon professionnelle, même en visio</li>
  <li>Éteignez votre téléphone</li>
  <li>Envoyez un email de remerciement dans les 24h après l'entretien</li>
</ul>
HTML,
            ],

            [
                'titre'        => 'Le marché de l\'emploi au Bénin en 2026 : tendances et secteurs porteurs',
                'slug'         => 'marche-emploi-benin-2026',
                'image'        => 'blog/article-marche-emploi.svg',
                'categorie'    => 'Marché de l\'emploi',
                'temps_lecture'=> 6,
                'extrait'      => 'Quels secteurs recrutent le plus au Bénin ? Quelles compétences sont les plus recherchées ? Tour d\'horizon du marché de l\'emploi béninois en 2026.',
                'contenu'      => <<<HTML
<p>Le marché de l'emploi au Bénin connaît des transformations profondes. La digitalisation de l'économie, la croissance du secteur agricole et le développement des infrastructures créent de nouvelles opportunités. Voici ce que vous devez savoir pour vous positionner stratégiquement.</p>

<h2>Les secteurs qui recrutent le plus en 2026</h2>

<h3>1. Technologies de l'information et digital</h3>
<p>Le numérique est en plein essor au Bénin. Les startups tech, les administrations publiques en cours de digitalisation et les entreprises privées recherchent massivement des <strong>développeurs web et mobile, des data analysts, des spécialistes cybersécurité et des UX designers</strong>. Les profils tech sont parmi les mieux rémunérés du marché.</p>

<h3>2. Agriculture et agro-industrie</h3>
<p>Premier secteur de l'économie béninoise, l'agriculture se modernise. Les entreprises de l'agro-industrie cherchent des <strong>ingénieurs agronomes, des techniciens en irrigation, des experts en supply chain agricole</strong> et des spécialistes en financement agricole.</p>

<h3>3. BTP et infrastructures</h3>
<p>Les grands chantiers publics et privés au Bénin créent une demande forte pour les <strong>ingénieurs en génie civil, les conducteurs de travaux, les topographes et les architectes</strong>. Cotonou et les villes secondaires connaissent une expansion immobilière significative.</p>

<h3>4. Finance et microfinance</h3>
<p>Le secteur financier, notamment les banques, les IMF (institutions de microfinance) et les fintechs, recrute des <strong>comptables, analystes financiers, chargés de clientèle et responsables conformité</strong>.</p>

<h3>5. Santé et pharmaceutique</h3>
<p>La demande en professionnels de santé dépasse largement l'offre. Les hôpitaux publics et cliniques privées recherchent médecins, infirmiers, pharmaciens et gestionnaires de santé.</p>

<h2>Les compétences les plus valorisées</h2>
<ul>
  <li><strong>Maîtrise du numérique :</strong> Pack Office, outils collaboratifs, outils métier</li>
  <li><strong>Langues :</strong> français courant + anglais professionnel (voire portugais pour les échanges avec le Nigeria et les pays lusophones)</li>
  <li><strong>Communication et leadership :</strong> de plus en plus valorisés à tous les niveaux</li>
  <li><strong>Gestion de projet :</strong> les entreprises veulent des profils capables d'autonomie</li>
  <li><strong>Compétences digitales spécifiques :</strong> marketing digital, e-commerce, réseaux sociaux professionnels</li>
</ul>

<h2>Les fourchettes salariales indicatives</h2>
<ul>
  <li>Débutant (0-2 ans) : 60 000 – 120 000 FCFA/mois</li>
  <li>Intermédiaire (2-5 ans) : 120 000 – 250 000 FCFA/mois</li>
  <li>Senior (5+ ans) : 250 000 – 500 000 FCFA/mois et plus</li>
  <li>Profils tech qualifiés : jusqu'à 800 000 FCFA/mois pour les experts</li>
</ul>

<h2>Conclusion</h2>
<p>Le Bénin offre de réelles opportunités pour les candidats qui investissent dans leur formation continue et leur réseau professionnel. Restez à l'affût des offres sur <strong>Emploi Bouge Bénin</strong> et n'hésitez pas à postuler, même si vous ne remplissez pas 100 % des critères.</p>
HTML,
            ],

            [
                'titre'        => 'Comment négocier votre salaire sans perdre l\'offre d\'emploi',
                'slug'         => 'negocier-salaire-emploi',
                'image'        => 'blog/article-salaire.svg',
                'categorie'    => 'Carrière',
                'temps_lecture'=> 5,
                'extrait'      => 'La négociation salariale est une étape que beaucoup évitent par peur. Pourtant, bien menée, elle peut faire une vraie différence sur votre rémunération.',
                'contenu'      => <<<HTML
<p>Négocier son salaire reste l'un des sujets qui génère le plus d'anxiété chez les candidats. Pourtant, c'est une étape normale du processus de recrutement et la majorité des recruteurs s'y attendent. Voici comment procéder avec confiance.</p>

<h2>Quand négocier ?</h2>
<p>Attendez toujours qu'une offre formelle vous soit faite avant de parler de salaire. Négocier trop tôt peut vous désavantager. Idéalement, c'est après avoir reçu la proposition écrite ou lors du dernier entretien, quand le recruteur vous signale que vous êtes le candidat retenu.</p>

<h2>Comment vous préparer ?</h2>
<ul>
  <li><strong>Faites vos recherches :</strong> connaissez les salaires pratiqués dans votre secteur, votre ville et pour votre niveau d'expérience</li>
  <li><strong>Définissez votre plancher :</strong> le salaire minimum en dessous duquel vous ne pouvez pas accepter</li>
  <li><strong>Définissez votre cible :</strong> le salaire idéal que vous souhaitez obtenir</li>
  <li><strong>Pensez au-delà du salaire :</strong> avantages en nature, formation, flexibilité, prime peuvent compenser un salaire de base inférieur</li>
</ul>

<h2>Les phrases qui fonctionnent</h2>
<p>Voici quelques formulations efficaces :</p>
<ul>
  <li><em>"En tenant compte de mes [X années d'expérience / certifications / réalisations], j'espérais un salaire autour de [montant]. Est-ce que c'est envisageable ?"</em></li>
  <li><em>"J'ai reçu d'autres propositions mais votre entreprise est ma préférence. Serait-il possible de revoir légèrement le salaire pour me permettre de vous rejoindre ?"</em></li>
  <li><em>"Je suis très enthousiaste à l'idée de rejoindre votre équipe. Serait-il possible d'envisager [montant] au lieu de [offre] ?"</em></li>
</ul>

<h2>Ce qu'il ne faut pas faire</h2>
<ul>
  <li>Ne mentez pas sur vos salaires précédents — les recruteurs vérifient parfois</li>
  <li>N'acceptez pas immédiatement une première offre sans au moins demander si c'est négociable</li>
  <li>N'exprimez pas de désespoir ou de besoin financier personnel — restez professionnel</li>
  <li>Ne posez pas d'ultimatum agressif si vous n'êtes pas prêt à le respecter</li>
</ul>

<h2>Si le salaire ne peut pas bouger...</h2>
<p>Demandez ce qui est négociable : jours de télétravail, prime de performance, prise en charge du transport, formations payées, révision salariale après 6 mois de période d'essai. Ces éléments ont une valeur réelle.</p>

<h2>Conclusion</h2>
<p>Négocier n'est pas une agression, c'est du professionnalisme. Un recruteur qui vous retire une offre parce que vous avez osé négocier poliment est un signal négatif sur la culture de l'entreprise. Ayez confiance en votre valeur.</p>
HTML,
            ],

            [
                'titre'        => 'Freelance ou emploi salarié : quel choix pour votre carrière au Bénin ?',
                'slug'         => 'freelance-vs-salarie-benin',
                'image'        => 'blog/article-freelance.svg',
                'categorie'    => 'Carrière',
                'temps_lecture'=> 6,
                'extrait'      => 'De plus en plus de professionnels béninois choisissent le freelance. Avantages, inconvénients, revenus, statut légal : tout ce qu\'il faut savoir avant de vous lancer.',
                'contenu'      => <<<HTML
<p>Le travail indépendant (freelance) connaît une croissance rapide au Bénin, portée par la digitalisation et l'accès à des plateformes mondiales. Mais est-ce vraiment fait pour vous ? Voici une analyse honnête des deux options.</p>

<h2>Le travail salarié : sécurité et structure</h2>
<h3>Les avantages</h3>
<ul>
  <li><strong>Revenu stable et prévisible</strong> chaque fin de mois</li>
  <li><strong>Protection sociale :</strong> CNSS, assurance maladie, congés payés</li>
  <li><strong>Accès aux prêts bancaires</strong> plus facile (fiche de paie = garantie)</li>
  <li><strong>Formation et montée en compétences</strong> financées par l'employeur</li>
  <li><strong>Réseau professionnel</strong> développé au sein de l'entreprise</li>
</ul>
<h3>Les inconvénients</h3>
<ul>
  <li>Revenu plafonné et progression souvent lente</li>
  <li>Moins de liberté dans l'organisation du travail</li>
  <li>Dépendance à un seul employeur</li>
</ul>

<h2>Le freelance : liberté et responsabilité</h2>
<h3>Les avantages</h3>
<ul>
  <li><strong>Revenus potentiellement illimités</strong> — vous fixez vos tarifs</li>
  <li><strong>Flexibilité totale</strong> sur vos horaires et lieu de travail</li>
  <li><strong>Diversité des missions</strong> — vous ne vous ennuyez jamais</li>
  <li><strong>Accès au marché international</strong> via des plateformes comme Upwork, Fiverr, Malt</li>
  <li><strong>Développement rapide</strong> des compétences commerciales et relationnelles</li>
</ul>
<h3>Les inconvénients</h3>
<ul>
  <li>Revenus irréguliers, surtout au début</li>
  <li>Pas de protection sociale automatique — vous devez vous assurer vous-même</li>
  <li>Gestion administrative : factures, impôts, comptabilité</li>
  <li>Isolement professionnel possible</li>
  <li>Prospection clients permanente</li>
</ul>

<h2>Les métiers les plus demandés en freelance au Bénin</h2>
<ul>
  <li>Développement web et mobile</li>
  <li>Design graphique et UI/UX</li>
  <li>Rédaction et traduction</li>
  <li>Marketing digital et gestion des réseaux sociaux</li>
  <li>Comptabilité et conseil fiscal</li>
  <li>Formation et e-learning</li>
</ul>

<h2>Comment bien démarrer en freelance au Bénin</h2>
<ol>
  <li>Construisez un portfolio avec des projets réels (même bénévoles au début)</li>
  <li>Créez un profil complet sur LinkedIn et les plateformes freelance</li>
  <li>Définissez votre tarif journalier en vous basant sur le marché local ET international</li>
  <li>Déclarez votre activité pour être en règle fiscalement</li>
  <li>Constituez un fonds de sécurité (3-6 mois de charges) avant de quitter un emploi salarié</li>
</ol>

<h2>Notre avis</h2>
<p>Le choix dépend de votre profil, de votre situation familiale et de votre appétit pour le risque. Beaucoup commencent par cumuler les deux (freelance en parallèle d'un emploi salarié) avant de basculer complètement. Il n'y a pas de mauvaise réponse — il y a votre réponse.</p>
HTML,
            ],

            [
                'titre'        => 'Comment trouver un emploi à Cotonou quand on est débutant',
                'slug'         => 'trouver-emploi-cotonou-debutant',
                'image'        => 'blog/article-debutant.svg',
                'categorie'    => 'Conseils',
                'temps_lecture'=> 5,
                'extrait'      => 'Jeune diplômé sans expérience ? Ces stratégies concrètes vous aideront à décrocher votre premier poste à Cotonou et au Bénin.',
                'contenu'      => <<<HTML
<p>Vous venez de terminer vos études et vous vous heurtez à la même réalité que beaucoup : les offres d'emploi demandent de l'expérience, mais pour avoir de l'expérience, il faut un emploi. Ce cercle vicieux peut être brisé. Voici comment.</p>

<h2>1. Acceptez des stages ou CDD pour commencer</h2>
<p>Votre premier objectif n'est pas le salaire idéal — c'est d'<strong>accumuler de l'expérience</strong>. Un stage bien choisi dans une entreprise reconnue vaut mieux qu'une longue période de chômage. Montrez votre motivation et faites-vous remarquer pour être converti en CDI.</p>

<h2>2. Développez des compétences concrètes</h2>
<p>En attendant votre premier emploi, ne restez pas inactif. Des plateformes gratuites ou abordables vous permettent d'apprendre :</p>
<ul>
  <li><strong>Coursera, edX, Google Skills</strong> pour les formations certifiantes</li>
  <li><strong>YouTube</strong> pour les tutoriels techniques</li>
  <li><strong>OpenClassrooms</strong> (disponible en français) pour le digital et le développement</li>
</ul>
<p>Une certification sur votre CV fait la différence même sans expérience.</p>

<h2>3. Activez votre réseau</h2>
<p>Au Bénin, une grande partie des emplois se trouvent grâce au <strong>bouche-à-oreille</strong>. Parlez de votre recherche à :</p>
<ul>
  <li>Vos professeurs et encadreurs de stage</li>
  <li>Vos anciens camarades de classe</li>
  <li>Les associations professionnelles de votre secteur</li>
  <li>Vos contacts LinkedIn</li>
</ul>

<h2>4. Candidatez de façon proactive</h2>
<p>N'attendez pas les offres publiées. Identifiez les entreprises qui vous intéressent à Cotonou et envoyez des <strong>candidatures spontanées</strong>. Appelez les DRH directement. Cette démarche proactive est appréciée et peut déboucher sur des opportunités non annoncées.</p>

<h2>5. Soignez votre présence en ligne</h2>
<p>Aujourd'hui, les recruteurs cherchent vos noms sur Google et LinkedIn avant de vous recevoir. Assurez-vous que votre profil LinkedIn est complet, professionnel, avec une photo de qualité. Nettoyez vos réseaux sociaux des contenus non professionnels.</p>

<h2>6. Déposez votre CV sur Emploi Bouge Bénin</h2>
<p>Notre plateforme est connectée à des centaines de recruteurs au Bénin. Déposez votre CV gratuitement pour être visible et recevoir des alertes emploi personnalisées selon votre profil.</p>

<h2>Conclusion</h2>
<p>Trouver son premier emploi demande de la patience, de la persévérance et de la stratégie. Chaque refus est une occasion d'améliorer votre candidature. Ne vous découragez pas — votre premier "oui" va arriver.</p>
HTML,
            ],
        ];

        foreach ($articles as $data) {
            Article::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, [
                    'auteur_id' => $admin->id,
                    'statut'    => 'publie',
                    'publie_le' => now(),
                ])
            );
        }

        $this->command->info('✅ ' . count($articles) . ' articles insérés/mis à jour avec succès.');
    }
}
