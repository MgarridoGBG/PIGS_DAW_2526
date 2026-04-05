<?php

namespace Database\Factories;

use App\Enums\EstadoCita;
use App\Enums\TurnoCita;
use Illuminate\Database\Eloquent\Factories\Factory;

class CitaFactory extends Factory
{
    /**
     * Factoria para el modelo Cita.
     *
     * @var string
     */
    protected $model = \App\Models\Cita::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [ // Genera una fecha de cita aleatoria, un turno y estado predefinidos, y asocia un usuario creado por la fábrica de User.
            'fecha_cita' => $this->faker->date(),
            'turno' => TurnoCita::MANANA->value,
            'estado_cita' => EstadoCita::SOLICITADA->value,
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
