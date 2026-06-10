<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('competences_candidat')) {
            return;
        }

        $oldRows = DB::table('competences_candidat')->get(['candidat_id', 'nom']);

        foreach ($oldRows as $row) {
            $nom = trim($row->nom);
            if (empty($nom)) {
                continue;
            }

            $slug = Str::slug($nom);
            if (empty($slug)) {
                continue;
            }

            DB::table('competences')->insertOrIgnore([
                'nom'        => $nom,
                'slug'       => $slug,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $competenceId = DB::table('competences')->where('slug', $slug)->value('id');

            if ($competenceId) {
                DB::table('competence_candidat')->insertOrIgnore([
                    'candidat_id'      => $row->candidat_id,
                    'competence_id'    => $competenceId,
                    'annees_experience' => null,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
            }
        }

        Schema::dropIfExists('competences_candidat');
    }

    public function down(): void
    {
        Schema::create('competences_candidat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidat_id')->constrained('users')->onDelete('cascade');
            $table->string('nom', 100);
            $table->enum('niveau', ['debutant', 'intermediaire', 'avance', 'expert'])->default('intermediaire');
            $table->timestamps();
            $table->index('candidat_id');
        });
    }
};
