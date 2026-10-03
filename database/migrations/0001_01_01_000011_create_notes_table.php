<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained('etudiants')->cascadeOnDelete();
            $table->foreignId('matiere_id')->constrained('matieres')->cascadeOnDelete();
            $table->unsignedTinyInteger('trimestre'); // 1, 2 ou 3
            $table->decimal('note_devoir', 5, 2)->nullable();
            $table->decimal('note_composition', 5, 2)->nullable();
            $table->string('annee_scolaire');
            $table->timestamps();

            $table->unique(['etudiant_id', 'matiere_id', 'trimestre', 'annee_scolaire'], 'note_unique_par_periode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
