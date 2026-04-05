<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FormatoFactory extends Factory
{
    /**
     * Factoria para el modelo Formato.
     * para poder ejectutar pruebas unitarias y de integración con datos de prueba.
     *
     * @var string
     */
    protected $model = \App\Models\Formato::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre_format' => $this->faker->unique()->words(2, true),
            'ancho' => $this->faker->randomFloat(2, 1, 200),
            'alto' => $this->faker->randomFloat(2, 1, 200),
        ];
    }
}
