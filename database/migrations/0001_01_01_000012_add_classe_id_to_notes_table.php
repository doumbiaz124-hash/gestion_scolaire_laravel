<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            // Conserve la classe de l'étudiant au moment de la saisie, pour que les bulletins
            // passés restent corrects même après un passage en classe supérieure.
            $table->foreignId('classe_id')->nullable()->after('matiere_id')
                ->constrained('classes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('classe_id');
        });
    }
};
