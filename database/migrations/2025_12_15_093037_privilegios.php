<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\NombrePrivilegio;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabla privilegios
        Schema::create('privilegios', function (Blueprint $table) {
            $table->id();

            $table->enum('nombre_priv', NombrePrivilegio::values());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('privilegios');
    }
};
