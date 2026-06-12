<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_pages', function (Blueprint $table) {
            $table->id();
            $table->string('page_slug', 60)->unique();
            $table->string('meta_title', 200)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('og_title', 200)->nullable();
            $table->string('og_description', 500)->nullable();
            $table->string('og_image_url', 500)->nullable();
            $table->boolean('noindex')->default(false);
            $table->boolean('nofollow')->default(false);
            $table->timestamps();
        });

        $now = now();
        DB::table('seo_pages')->insert([
            ['page_slug' => 'home',     'meta_title' => "Emploi Bouge Bénin — Offres d'emploi, CV et recrutement",     'meta_description' => "Trouvez votre prochain emploi au Bénin. Offres CDI, CDD, Stage. Déposez votre CV. Recrutez les meilleurs talents.",         'og_title' => null, 'og_description' => null, 'og_image_url' => null, 'noindex' => false, 'nofollow' => false, 'created_at' => $now, 'updated_at' => $now],
            ['page_slug' => 'offres',   'meta_title' => "Offres d'emploi au Bénin — Emploi Bouge Bénin",               'meta_description' => "Parcourez toutes les offres d'emploi disponibles au Bénin : CDI, CDD, stage, freelance. Postulez en quelques clics.",      'og_title' => null, 'og_description' => null, 'og_image_url' => null, 'noindex' => false, 'nofollow' => false, 'created_at' => $now, 'updated_at' => $now],
            ['page_slug' => 'cvs',      'meta_title' => 'CVthèque — Trouvez des candidats qualifiés au Bénin',         'meta_description' => 'Consultez notre CVthèque et découvrez des profils qualifiés pour vos recrutements au Bénin.',                           'og_title' => null, 'og_description' => null, 'og_image_url' => null, 'noindex' => false, 'nofollow' => false, 'created_at' => $now, 'updated_at' => $now],
            ['page_slug' => 'blog',     'meta_title' => 'Blog emploi et carrière — Emploi Bouge Bénin',                'meta_description' => "Conseils emploi, guides recrutement, actualités du marché du travail au Bénin.",                                       'og_title' => null, 'og_description' => null, 'og_image_url' => null, 'noindex' => false, 'nofollow' => false, 'created_at' => $now, 'updated_at' => $now],
            ['page_slug' => 'apropos',  'meta_title' => 'À propos — Emploi Bouge Bénin',                               'meta_description' => "Découvrez Emploi Bouge Bénin, la plateforme de référence pour l'emploi et le recrutement au Bénin.",                'og_title' => null, 'og_description' => null, 'og_image_url' => null, 'noindex' => false, 'nofollow' => false, 'created_at' => $now, 'updated_at' => $now],
            ['page_slug' => 'contact',  'meta_title' => 'Contact — Emploi Bouge Bénin',                                'meta_description' => "Contactez l'équipe Emploi Bouge Bénin pour toute question, partenariat ou assistance.",                              'og_title' => null, 'og_description' => null, 'og_image_url' => null, 'noindex' => false, 'nofollow' => false, 'created_at' => $now, 'updated_at' => $now],
            ['page_slug' => 'services', 'meta_title' => 'Services RH — Emploi Bouge Bénin',                            'meta_description' => "Découvrez nos services RH : rédaction de CV, coaching carrière, accompagnement à la recherche d'emploi au Bénin.",   'og_title' => null, 'og_description' => null, 'og_image_url' => null, 'noindex' => false, 'nofollow' => false, 'created_at' => $now, 'updated_at' => $now],
            ['page_slug' => 'faq',      'meta_title' => 'FAQ — Questions fréquentes — Emploi Bouge Bénin',             'meta_description' => "Retrouvez les réponses aux questions fréquentes sur Emploi Bouge Bénin.",                                            'og_title' => null, 'og_description' => null, 'og_image_url' => null, 'noindex' => false, 'nofollow' => false, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('parametres_app')->insertOrIgnore([
            ['cle' => 'ga_measurement_id', 'valeur' => '', 'label' => 'Google Analytics 4 — Measurement ID (ex: G-XXXXXXXXXX)'],
            ['cle' => 'gsc_verification',  'valeur' => '', 'label' => 'Google Search Console — Code de vérification HTML'],
            ['cle' => 'og_image_default',  'valeur' => '', 'label' => 'Image OG par défaut (URL absolue)'],
            ['cle' => 'robots_txt_extra',  'valeur' => '', 'label' => 'Lignes supplémentaires pour robots.txt'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_pages');
        DB::table('parametres_app')
            ->whereIn('cle', ['ga_measurement_id', 'gsc_verification', 'og_image_default', 'robots_txt_extra'])
            ->delete();
    }
};
