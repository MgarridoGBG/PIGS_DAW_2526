<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factoria para el modelo Soporte.
 *
 * @var string
 */
class SoporteFactory extends Factory
{
    protected $model = \App\Models\Soporte::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre_soport' => $this->faker->unique()->word(),
            'disponibilidad' => $this->faker->boolean(80),
            'precio' => $this->faker->randomFloat(2, 1, 200),
        ];
    }
}
