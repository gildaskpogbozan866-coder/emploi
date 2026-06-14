<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            ['categorie' => 'Candidats', 'ordre' => 1, 'question' => 'Est-ce gratuit pour les candidats ?',
             'reponse' => 'Oui, totalement gratuit. Vous pouvez déposer votre CV, postuler aux offres et créer des alertes emploi sans débourser un centime. Un plan Premium optionnel est disponible pour plus de visibilité.'],
            ['categorie' => 'Candidats', 'ordre' => 2, 'question' => 'Comment fonctionne le dépôt de CV ?',
             'reponse' => 'Créez un compte, remplissez votre profil, et déposez votre CV en quelques minutes. Votre profil sera visible par les recruteurs de notre réseau.'],
            ['categorie' => 'Candidats', 'ordre' => 3, 'question' => 'Puis-je postuler à plusieurs offres ?',
             'reponse' => 'Oui, le nombre de candidatures est illimité sur le plan gratuit.'],
            ['categorie' => 'Candidats', 'ordre' => 4, 'question' => 'Comment recevoir des alertes emploi ?',
             'reponse' => "Dans votre espace candidat, créez des alertes basées sur des mots-clés, localisation ou type de contrat. Vous serez notifié(e) par email selon la fréquence choisie."],

            ['categorie' => 'Recruteurs', 'ordre' => 1, 'question' => "Comment publier une offre d'emploi ?",
             'reponse' => "Créez un compte recruteur, remplissez le formulaire de publication et soumettez votre offre. Elle sera vérifiée et publiée sous 24h ouvrées."],
            ['categorie' => 'Recruteurs', 'ordre' => 2, 'question' => "Combien coûte la publication d'une offre ?",
             'reponse' => 'La première offre est gratuite. Des plans premium sont disponibles pour booster la visibilité et publier plusieurs offres.'],
            ['categorie' => 'Recruteurs', 'ordre' => 3, 'question' => 'Puis-je accéder aux CV des candidats ?',
             'reponse' => "Oui, notre CVthèque est accessible aux recruteurs enregistrés. Un abonnement premium donne accès aux coordonnées complètes."],

            ['categorie' => 'Général', 'ordre' => 1, 'question' => 'Les offres sont-elles vérifiées ?',
             'reponse' => "Toutes les offres passent par notre équipe de modération avant publication. Nous vérifions l'existence de l'entreprise et la légitimité de l'offre."],
            ['categorie' => 'Général', 'ordre' => 2, 'question' => 'Comment signaler une offre suspecte ?',
             'reponse' => 'Sur chaque offre, un lien "Signaler" permet de nous alerter. Notre équipe traite tous les signalements sous 48h.'],
            ['categorie' => 'Général', 'ordre' => 3, 'question' => 'Quels pays sont couverts ?',
             'reponse' => "La plateforme est principalement axée sur le Bénin, mais couvre également la Côte d'Ivoire, le Sénégal, le Togo, le Cameroun et d'autres pays d'Afrique francophone."],
        ];

        foreach ($faqs as $data) {
            Faq::create(array_merge($data, ['actif' => true]));
        }
    }
}
