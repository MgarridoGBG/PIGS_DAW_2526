<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EtiquetaFactory extends Factory
{
    /**
     * Factoria para el modelo Cita.
     *
     * @var string
     */
    protected $model = \App\Models\Etiqueta::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre_etiqueta' => $this->faker->unique()->word(),
        ];
    }
}
