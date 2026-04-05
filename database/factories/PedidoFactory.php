<?php

namespace Database\Factories;

use App\Enums\EstadoPedido;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factoria para el modelo Pedido.
 *
 * @var string
 */
class PedidoFactory extends Factory
{
    protected $model = \App\Models\Pedido::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'estado_pedido' => EstadoPedido::EMITIDO->value,
            'fecha_pedido' => $this->faker->date(),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
