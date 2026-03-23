<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fotografias_etiquetas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fotografia_id')->constrained('fotografias')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('etiqueta_id')->constrained('etiquetas')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unique(['fotografia_id', 'etiqueta_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fotografias_etiquetas');
    }
};
