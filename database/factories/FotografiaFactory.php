<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FotografiaFactory extends Factory
    /**
     * Factoria para el modelo Fotografia.
     *
     * @var string
     */
{
    protected $model = \App\Models\Fotografia::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre_foto' => $this->faker->unique()->lexify('foto_????'),
            'reportaje_id' => \App\Models\Reportaje::factory(),
        ];
    }
}
