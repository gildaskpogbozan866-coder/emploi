<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('cv_credits')->default(0)->after('role');
        });

        Schema::table('cvs', function (Blueprint $table) {
            $table->enum('disponibilite', ['en_recherche', 'ouvert', 'indisponible'])->nullable()->after('visible');
            $table->text('resume')->nullable()->after('disponibilite');
            $table->string('secteur', 100)->nullable()->after('resume');
        });

        Schema::create('cv_downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruteur_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('cv_id')->constrained('cvs')->cascadeOnDelete();
            $table->timestamp('downloaded_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cv_downloads');

        Schema::table('cvs', function (Blueprint $table) {
            $table->dropColumn(['disponibilite', 'resume', 'secteur']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('cv_credits');
        });
    }
};
