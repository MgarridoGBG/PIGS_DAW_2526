<?php

namespace Database\Factories;

use App\Enums\TipoReportaje;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factoria para el modelo Reportaje.
 *
 * @var string
 */
class ReportajeFactory extends Factory
{
    protected $model = \App\Models\Reportaje::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tipo' => TipoReportaje::OTRO->value,
            'codigo' => strtoupper($this->faker->unique()->bothify('REPOR####')),
            'descripcion' => $this->faker->text(150),
            'fecha_report' => $this->faker->date(),
            'publico' => $this->faker->boolean(30),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
