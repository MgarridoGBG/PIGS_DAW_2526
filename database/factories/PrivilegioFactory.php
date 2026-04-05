<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factoria para el modelo Privilegio.
 *
 * @var string
 */
class PrivilegioFactory extends Factory
{
    protected $model = \App\Models\Privilegio::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [ // Genera un nombre de privilegio único utilizando Faker.
            'nombre_priv' => $this->faker->unique()->word(),
        ];
    }
}
