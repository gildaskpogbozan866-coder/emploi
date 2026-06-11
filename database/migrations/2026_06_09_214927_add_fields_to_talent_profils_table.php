<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Convertir compétences et langues en JSON avant de modifier le schéma
        DB::table('talent_profils')->orderBy('id')->each(function ($row) {
            $competences = [];
            if ($row->competences) {
                $competences = array_values(array_filter(
                    array_map('trim', explode(',', $row->competences)),
                    fn($c) => $c !== ''
                ));
            }

            $langues = [];
            if ($row->langues) {
                foreach (array_filter(array_map('trim', explode(',', $row->langues))) as $l) {
                    $langues[] = ['langue' => $l, 'niveau' => 'B2'];
                }
            }

            DB::table('talent_profils')->where('id', $row->id)->update([
                'competences' => json_encode($competences),
                'langues'     => json_encode($langues),
            ]);
        });

        Schema::table('talent_profils', function (Blueprint $table) {
            $table->integer('tjm')->nullable()->after('portfolio_url');
            $table->enum('disponibilite', ['immediatement', '1_mois', '2_mois', '3_mois', 'plus_3_mois'])
                  ->nullable()->after('tjm');
            $table->json('types_mission')->nullable()->after('disponibilite');
            $table->tinyInteger('annees_experience')->unsigned()->nullable()->after('experience');
        });
    }

    public function down(): void
    {
        Schema::table('talent_profils', function (Blueprint $table) {
            $table->dropColumn(['tjm', 'disponibilite', 'types_mission', 'annees_experience']);
        });
    }
};
