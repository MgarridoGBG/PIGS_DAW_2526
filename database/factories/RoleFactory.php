<?php

namespace Database\Factories;

use App\Enums\NombreRole;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factoria para el modelo Role.
 *
 * @var string
 */
class RoleFactory extends Factory
{
    protected $model = \App\Models\Role::class;

    /*
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre_role' => NombreRole::INVITADO->value,
        ];
    }
}
