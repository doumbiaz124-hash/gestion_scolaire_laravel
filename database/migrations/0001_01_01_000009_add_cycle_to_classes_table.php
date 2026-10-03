<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->enum('cycle', ['jardin', 'premier_cycle', 'second_cycle', 'lycee'])
                ->default('premier_cycle')
                ->after('frais_scolaire');
            $table->foreignId('classe_suivante_id')->nullable()
                ->after('cycle')
                ->constrained('classes')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('classe_suivante_id');
            $table->dropColumn('cycle');
        });
    }
};
