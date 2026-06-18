<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        Faq::truncate();

        $faqs = [

            // ── CANDIDATS ──────────────────────────────────────────
            ['categorie' => 'Candidats', 'ordre' => 1,
             'question' => 'Est-ce gratuit pour les candidats ?',
             'reponse'  => "Oui, totalement gratuit. Vous pouvez créer votre profil, déposer votre CV, postuler à autant d'offres que vous le souhaitez et recevoir des alertes emploi sans débourser un centime. Un plan Premium optionnel est disponible pour booster votre visibilité auprès des recruteurs."],

            ['categorie' => 'Candidats', 'ordre' => 2,
             'question' => 'Comment déposer mon CV sur la plateforme ?',
             'reponse'  => "Créez un compte en choisissant le rôle « Candidat », remplissez votre profil (formation, expériences, compétences, disponibilité) et cliquez sur « Déposer mon CV ». Votre profil sera immédiatement visible par les recruteurs. Vous pouvez aussi uploader un document PDF ou Word."],

            ['categorie' => 'Candidats', 'ordre' => 3,
             'question' => 'Puis-je postuler à plusieurs offres en même temps ?',
             'reponse'  => "Oui, le nombre de candidatures est illimité. Vous pouvez postuler à autant d'offres que vous le souhaitez depuis votre espace candidat, et suivre l'état de chaque candidature en temps réel."],

            ['categorie' => 'Candidats', 'ordre' => 4,
             'question' => 'Comment activer les alertes emploi ?',
             'reponse'  => "Dans votre espace candidat, accédez à « Mes alertes » et créez des alertes basées sur des mots-clés, une localisation, un type de contrat (CDI, CDD, Stage, etc.) ou un secteur d'activité. Vous serez notifié par email dès qu'une offre correspondante est publiée."],

            ['categorie' => 'Candidats', 'ordre' => 5,
             'question' => 'Mon CV est-il visible par tous les recruteurs ?',
             'reponse'  => "Oui, votre CV est visible dans la CVthèque publique. Cependant, certaines informations sensibles (téléphone, email complet) sont masquées pour les recruteurs non abonnés ; elles ne s'affichent que lorsqu'un recruteur premium consulte votre profil ou que vous postulez directement à une offre."],

            ['categorie' => 'Candidats', 'ordre' => 6,
             'question' => 'Comment savoir si ma candidature a été vue ?',
             'reponse'  => "Dans votre espace « Mes candidatures », vous pouvez voir le statut de chaque candidature : <strong>En attente</strong>, <strong>Vue</strong>, <strong>Présélectionné(e)</strong> ou <strong>Refusée</strong>. Vous recevez aussi une notification email à chaque changement de statut."],

            ['categorie' => 'Candidats', 'ordre' => 7,
             'question' => 'Puis-je désactiver mon profil temporairement ?',
             'reponse'  => "Oui. Dans vos paramètres de compte, vous pouvez passer votre disponibilité en « Non disponible » ou mettre votre CV en mode privé. Votre profil n'apparaîtra plus dans les recherches de recruteurs, mais votre compte reste actif."],

            // ── RECRUTEURS ─────────────────────────────────────────
            ['categorie' => 'Recruteurs', 'ordre' => 1,
             'question' => "Comment publier une offre d'emploi ?",
             'reponse'  => "Créez un compte recruteur, complétez les informations de votre entreprise et cliquez sur « Publier une annonce ». Remplissez le formulaire (titre, description, type de contrat, localisation, salaire…), soumettez : votre offre est examinée et publiée sous 24h ouvrées."],

            ['categorie' => 'Recruteurs', 'ordre' => 2,
             'question' => "Combien coûte la publication d'une offre ?",
             'reponse'  => "La première offre est gratuite. Des plans premium permettent de publier plusieurs offres simultanément, de les mettre en avant et d'accéder à la CVthèque complète. Consultez notre page <a href='/services' style='color:#185FA5;font-weight:600'>Tarifs</a> pour le détail des offres."],

            ['categorie' => 'Recruteurs', 'ordre' => 3,
             'question' => 'Puis-je accéder aux CV et coordonnées des candidats ?',
             'reponse'  => "Oui. Notre CVthèque est accessible aux recruteurs enregistrés. Avec un compte gratuit, vous voyez le profil et les compétences. Avec un abonnement premium, vous accédez aux coordonnées complètes (email, téléphone) et pouvez contacter les candidats directement."],

            ['categorie' => 'Recruteurs', 'ordre' => 4,
             'question' => 'Mon offre doit-elle être vérifiée avant publication ?',
             'reponse'  => "Oui. Toutes les offres passent par notre équipe de modération avant d'être publiées. Nous vérifions que l'entreprise est réelle, que l'offre est légitime et qu'elle respecte nos règles de publication. Ce processus prend en général moins de 24h ouvrées."],

            ['categorie' => 'Recruteurs', 'ordre' => 5,
             'question' => 'Combien de temps reste mon offre en ligne ?',
             'reponse'  => "Les offres restent en ligne selon le plan choisi : 30 jours pour les offres standards, jusqu'à 90 jours pour les offres premium. Vous pouvez renouveler ou désactiver votre offre à tout moment depuis votre tableau de bord."],

            ['categorie' => 'Recruteurs', 'ordre' => 6,
             'question' => 'Comment gérer les candidatures reçues ?',
             'reponse'  => "Dans votre tableau de bord recruteur, accédez à « Candidatures ». Vous pouvez voir tous les dossiers reçus par offre, télécharger les CV, changer le statut (présélectionné, refusé…) et envoyer des messages aux candidats directement depuis la plateforme."],

            // ── SERVICES ───────────────────────────────────────────
            ['categorie' => 'Services', 'ordre' => 1,
             'question' => "Quels services d'accompagnement proposez-vous ?",
             'reponse'  => "Nous proposons plusieurs services pour booster votre carrière : <ul style='margin:8px 0 0 18px'><li><strong>CV Professionnel</strong> : CV structuré et optimisé ATS, livré en Word + PDF</li><li><strong>Lettre de motivation</strong> : personnalisée et convaincante</li><li><strong>Coaching carrière</strong> : entretien simulé, stratégie de recherche</li><li><strong>Optimisation de profil LinkedIn</strong></li></ul>"],

            ['categorie' => 'Services', 'ordre' => 2,
             'question' => "Combien coûte la rédaction d'un CV professionnel ?",
             'reponse'  => "Le pack CV Professionnel est à <strong>2 500 FCFA tout compris</strong>. Il inclut : analyse de profil, CV structuré et optimisé ATS, livraison en Word & PDF, et 1 révision gratuite."],

            ['categorie' => 'Services', 'ordre' => 3,
             'question' => 'En combien de temps recevrai-je mon CV ?',
             'reponse'  => "Votre CV est livré <strong>en 30 minutes à 1 heure maximum</strong> après validation de votre commande et réception de vos informations. C'est notre engagement."],

            ['categorie' => 'Services', 'ordre' => 4,
             'question' => 'La révision est-elle vraiment gratuite ?',
             'reponse'  => "Oui. Après livraison, vous avez droit à <strong>1 révision gratuite</strong> si le document ne correspond pas à vos attentes. Il suffit de nous indiquer les modifications souhaitées et nous les appliquons dans l'heure."],

            ['categorie' => 'Services', 'ordre' => 5,
             'question' => 'Mes informations transmises pour le CV sont-elles confidentielles ?',
             'reponse'  => "Absolument. Toutes les informations que vous nous transmettez pour la rédaction de votre CV (parcours, diplômes, expériences) sont strictement confidentielles. Elles ne sont utilisées que pour réaliser votre document et ne sont jamais partagées ou revendues."],

            // ── PAIEMENTS ──────────────────────────────────────────
            ['categorie' => 'Paiements', 'ordre' => 1,
             'question' => 'Quels modes de paiement acceptez-vous ?',
             'reponse'  => "Nous acceptons les paiements via <strong>Mobile Money</strong> (MTN MoMo, Moov Money), les cartes bancaires (Visa, Mastercard) et d'autres moyens disponibles selon votre pays. Tous les paiements sont sécurisés via notre prestataire certifié."],

            ['categorie' => 'Paiements', 'ordre' => 2,
             'question' => 'Mon paiement est-il sécurisé ?',
             'reponse'  => "Oui. Nous ne stockons jamais vos données bancaires. Les transactions sont traitées par notre prestataire de paiement certifié (FedaPay). Vous recevez un reçu par email à chaque transaction réussie."],

            ['categorie' => 'Paiements', 'ordre' => 3,
             'question' => 'Puis-je obtenir un remboursement ?',
             'reponse'  => "Pour les services de rédaction (CV, lettre), si votre commande n'a pas encore été traitée, un remboursement est possible. Une fois le document livré, les paiements ne sont généralement pas remboursables sauf erreur de notre part. Contactez-nous dans les 24h suivant votre commande si vous souhaitez l'annuler."],

            ['categorie' => 'Paiements', 'ordre' => 4,
             'question' => 'Comment obtenir une facture ?',
             'reponse'  => "Une facture est automatiquement générée et envoyée par email après chaque paiement réussi. Vous pouvez aussi la télécharger depuis votre espace « Mes commandes »."],

            // ── GÉNÉRAL ────────────────────────────────────────────
            ['categorie' => 'Général', 'ordre' => 1,
             'question' => 'Les offres publiées sont-elles vérifiées ?',
             'reponse'  => "Oui. Toutes les offres sont soumises à une modération avant publication. Notre équipe vérifie l'existence de l'entreprise, la légitimité de l'offre et sa conformité à nos règles. Aucune offre frauduleuse ou discriminatoire n'est acceptée."],

            ['categorie' => 'Général', 'ordre' => 2,
             'question' => 'Quels pays sont couverts par la plateforme ?',
             'reponse'  => "La plateforme est principalement axée sur le <strong>Bénin</strong> (Cotonou, Porto-Novo, Parakou et toutes les villes). Elle couvre également la Côte d'Ivoire, le Sénégal, le Togo, le Cameroun, le Burkina Faso et d'autres pays d'Afrique francophone."],

            ['categorie' => 'Général', 'ordre' => 3,
             'question' => 'Comment signaler une offre frauduleuse ?',
             'reponse'  => "Sur chaque fiche d'offre, un lien « Signaler » est disponible. Vous pouvez aussi nous contacter directement par email ou WhatsApp. Notre équipe examine et traite tous les signalements sous 24h."],

            ['categorie' => 'Général', 'ordre' => 4,
             'question' => 'Comment supprimer mon compte ?',
             'reponse'  => "Dans vos paramètres de compte, cliquez sur « Supprimer mon compte ». Toutes vos données seront effacées définitivement sous 30 jours conformément à notre politique de confidentialité. Si vous rencontrez un problème, contactez notre support."],

            ['categorie' => 'Général', 'ordre' => 5,
             'question' => 'Comment contacter le support ?',
             'reponse'  => "Vous pouvez nous joindre par :<br><br><strong>WhatsApp :</strong> +229 01 51 92 98 56<br><strong>Email :</strong> <a href='mailto:Gildaskpogbozan866@gmail.com' style='color:#185FA5;font-weight:600'>Gildaskpogbozan866@gmail.com</a><br><br>Notre équipe répond en général dans l'heure en semaine."],
        ];

        foreach ($faqs as $data) {
            Faq::create(array_merge($data, ['actif' => true]));
        }

        $this->command->info('✅ ' . count($faqs) . ' FAQs insérées.');
    }
}
