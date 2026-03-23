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
Schema::create('items', function (Blueprint $table) {
    $table->id();
    $table->integer('cantidad');
    $table->decimal('precio', 8, 2)->default(0);
    $table->foreignId('pedido_id')->constrained('pedidos')->cascadeOnUpdate()->cascadeOnDelete();
    $table->foreignId('formato_id')->constrained('formatos')->cascadeOnUpdate()->cascadeOnDelete();
    $table->foreignId('soporte_id')->constrained('soportes')->cascadeOnUpdate()->cascadeOnDelete();
    $table->foreignId('fotografia_id')->constrained('fotografias')->cascadeOnUpdate()->cascadeOnDelete();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
