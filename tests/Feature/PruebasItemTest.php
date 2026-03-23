<?php

namespace Tests\Feature;

use App\Http\Controllers\ItemController;
use App\Models\Formato;
use App\Models\Item;
use App\Models\Soporte;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PruebasItemTest extends TestCase
{
    use RefreshDatabase;

        // calcularPrecio
    
    /**
     * Con formato y soporte existentes, calcula el precio correctamente.
     * Fórmula: round(((alto * ancho) * precio) / 10000, 2)
     */
    public function testCalcularPrecioBien(): void
    {
        $formato = Formato::factory()->create(['ancho' => 100.00, 'alto' => 200.00]);
        $soporte = Soporte::factory()->create(['precio' => 5.00]);
        $controlador = new ItemController();

        $resultado = $controlador->calcularPrecio($formato->id, $soporte->id);

        // ((200 * 100) * 5) / 10000 = 10.00
        $esperado = round(($formato->alto * $formato->ancho * $soporte->precio) / 10000, 2);
        $this->assertSame($esperado, $resultado);
    }

        // borrarItemPedido
    
    /**
     * Intentar borrar un item que no existe devuelve la vista errores.error.
     */
    public function testFalloBorrarItemNoExiste(): void
    {
        $response = $this->withoutMiddleware()
            ->delete(route('borraritempedido', 99999));

        $response->assertStatus(200);
        $response->assertViewIs('errores.error');
    }
}
