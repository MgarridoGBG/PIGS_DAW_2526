<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\TipoReportaje;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reportajes', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', TipoReportaje::values())->default(TipoReportaje::OTRO->value);
            $table->string('codigo', 20)->unique();
            $table->string('descripcion', 250)->nullable();
            $table->date('fecha_report');
            $table->boolean('publico')->default(false);
            $table->foreignId('user_id')->nullable()
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('set null');
                // Los reportajes se mantienen aunque se borre el usuario.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportajes');
    }
};
