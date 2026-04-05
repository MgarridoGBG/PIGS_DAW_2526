<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    /**
     * Factoria para el modelo Item.
     *
     * @var string
     */
    protected $model = \App\Models\Item::class;

    public function definition(): array
    {
        return [ // Genera una cantidad aleatoria entre 1 y 10, un precio aleatorio con dos decimales entre 1 y 100, y asocia un pedido, formato, soporte y fotografía creados por sus respectivas fábricas.
            'cantidad' => $this->faker->numberBetween(1, 10),
            'precio' => $this->faker->randomFloat(2, 1, 100),
            'pedido_id' => \App\Models\Pedido::factory(),
            'formato_id' => \App\Models\Formato::factory(),
            'soporte_id' => \App\Models\Soporte::factory(),
            'fotografia_id' => \App\Models\Fotografia::factory(),
        ];
    }
}
