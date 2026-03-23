<?php

namespace Tests\Feature;

use App\Models\Formato;
use App\Models\Fotografia;
use App\Models\Soporte;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PruebasCarritoTest extends TestCase
{
    use RefreshDatabase;

        // Helper para construir items de carrito válidos
    
    /** Construye un item de carrito válido listo para meter en sesión. */
    private function itemCarritoValido(Fotografia $foto, Formato $format, Soporte $sopor, int $cantidad = 1): array
    {
        return [
            'fotografia_id' => $foto->id,
            'formato_id'    => $format->id,
            'soporte_id'    => $sopor->id,
            'precio'        => 5.00,
            'cantidad'      => $cantidad,
        ];
    }

        // registrarNuevoItemCarrito
    
    /**
     * Cantidad 0 error 'Cantidad inválida.'
     */
    public function testFalloCantidadCero(): void
    {
        $foto = Fotografia::factory()->create();
        $formato = Formato::factory()->create();
        $soporte = Soporte::factory()->create(['disponibilidad' => true]);

        $response = $this->withoutMiddleware()->post(route('procesaritemcarrito'), [
            'fotografia_id' => $foto->id,
            'formato'       => $formato->nombre_format,
            'soporte'       => $soporte->nombre_soport,
            'cantidad'      => 0,
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('errores.error');
    }

    /**
     * Cantidad negativa error 'Cantidad inválida.'
     */
    public function testFalloCantidadNegativa(): void
    {
        $foto = Fotografia::factory()->create();
        $formato = Formato::factory()->create();
        $soporte = Soporte::factory()->create(['disponibilidad' => true]);

        $response = $this->withoutMiddleware()->post(route('procesaritemcarrito'), [
            'fotografia_id' => $foto->id,
            'formato'       => $formato->nombre_format,
            'soporte'       => $soporte->nombre_soport,
            'cantidad'      => -3,
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('errores.error');
    }

    /**
     * Formato inexistente error 'Error al añadir el artículo.'
     */
    public function testFalloFormatoInexistente(): void
    {
        $foto = Fotografia::factory()->create();
        $soporte = Soporte::factory()->create(['disponibilidad' => true]);

        $response = $this->withoutMiddleware()->post(route('procesaritemcarrito'), [
            'fotografia_id' => $foto->id,
            'formato'       => 'formato_que_no_existe_xyzxyz',
            'soporte'       => $soporte->nombre_soport,
            'cantidad'      => 1,
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('errores.error');
    }

    /**
     * Soporte inexistente error 'Error al añadir el artículo.'
     */
    public function testFalloSoporteInexistente(): void
    {
        $foto = Fotografia::factory()->create();
        $formato = Formato::factory()->create();

        $response = $this->withoutMiddleware()->post(route('procesaritemcarrito'), [
            'fotografia_id' => $foto->id,
            'formato'       => $formato->nombre_format,
            'soporte'       => 'soporte_que_no_existe_xyzxyz',
            'cantidad'      => 1,
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('errores.error');
    }

    /**
     * fotografia_id inexistente: el controlador no lo rechaza en este punto,
     * pero el item queda guardado en sesión con id inválido.
     * Verificamos que no devuelve error y que la sesión contiene el item.
     */
    public function testFalloFotografiaInexistente(): void
    {
        $formato = Formato::factory()->create(['ancho' => 10, 'alto' => 10]);
        $soporte = Soporte::factory()->create(['disponibilidad' => true, 'precio' => 1.00]);

        $response = $this->withoutMiddleware()->post(route('procesaritemcarrito'), [
            'fotografia_id' => 99999,
            'formato'       => $formato->nombre_format,
            'soporte'       => $soporte->nombre_soport,
            'cantidad'      => 1,
        ]);

        // El controlador no revalida la existencia de la foto aquí
        $response->assertStatus(200);
        $response->assertSessionHas('Carrito');
    }

    /**
     * Petición válida completa guarda el item en sesión y devuelve la vista parcial.
     */
    public function testGuardaItemValido(): void
    {
        $foto    = Fotografia::factory()->create();
        $formato = Formato::factory()->create(['ancho' => 20, 'alto' => 30]);
        $soporte = Soporte::factory()->create(['disponibilidad' => true, 'precio' => 2.00]);

        $response = $this->withoutMiddleware()->post(route('procesaritemcarrito'), [
            'fotografia_id' => $foto->id,
            'formato'       => $formato->nombre_format,
            'soporte'       => $soporte->nombre_soport,
            'cantidad'      => 2,
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('parciales.itemanadidocarrito');
        $response->assertSessionHas('Carrito');

        $carrito = session('Carrito');
        $this->assertCount(1, $carrito);
        $this->assertEquals(2, $carrito[0]['cantidad']);
    }

    /**
     * Al llegar a 15 items, el siguiente intento devuelve error de límite.
     */
    public function testFalloLimite15Items(): void
    {
        $foto    = Fotografia::factory()->create();
        $formato = Formato::factory()->create(['ancho' => 10, 'alto' => 10]);
        $soporte = Soporte::factory()->create(['disponibilidad' => true, 'precio' => 1.00]);

        // Pre-llenamos la sesión con 15 items
        $carritoLleno = array_fill(0, 15, [
            'fotografia_id' => $foto->id,
            'formato_id'    => $formato->id,
            'soporte_id'    => $soporte->id,
            'precio'        => 1.00,
            'cantidad'      => 1,
        ]);

        $response = $this->withoutMiddleware()
            ->withSession(['Carrito' => $carritoLleno])
            ->post(route('procesaritemcarrito'), [
                'fotografia_id' => $foto->id,
                'formato'       => $formato->nombre_format,
                'soporte'       => $soporte->nombre_soport,
                'cantidad'      => 1,
            ]);

        $response->assertStatus(200);
        $response->assertViewIs('errores.error');
    }

        // procesarCarrito
    
    /**
     * Carrito vacío devuelve vista de error.
     */
    public function testFalloCarritoVacio(): void
    {
        $user = User::factory()->create();

        $response = $this->withoutMiddleware()
            ->actingAs($user)
            ->withSession(['Carrito' => []])
            ->post(route('procesarcarrito'));

        $response->assertStatus(200);
        $response->assertViewIs('errores.error');
    }

    /**
     * Carrito con items válidos crea el Pedido y sus Items en BD.
     */
    public function testProcesaCarritoCorrecto(): void
    {
        $user    = User::factory()->create();
        $foto    = Fotografia::factory()->create();
        $formato = Formato::factory()->create();
        $soporte = Soporte::factory()->create();

        $carrito = [$this->itemCarritoValido($foto, $formato, $soporte, 2)];

        $response = $this->withoutMiddleware()
            ->actingAs($user)
            ->withSession(['Carrito' => $carrito])
            ->post(route('procesarcarrito'));

        $response->assertStatus(200);
        $response->assertViewIs('errores.exito');

        $this->assertDatabaseHas('pedidos', [
            'user_id'       => $user->id,
            'estado_pedido' => 'emitido',
        ]);
        $this->assertDatabaseHas('items', [
            'fotografia_id' => $foto->id,
            'formato_id'    => $formato->id,
            'soporte_id'    => $soporte->id,
            'cantidad'      => 2,
        ]);
    }

    /**
     * Carrito con fotografia_id inválida: devuelve error y no crea ni Pedido ni Items en BD.
     * La FK de 'items.fotografia_id' dispara una excepción que revierte la transacción completa.
     */
    public function testFalloFotografiaInvalida(): void
    {
        $user    = User::factory()->create();
        $formato = Formato::factory()->create();
        $soporte = Soporte::factory()->create();

        // Creamos un carrito con un item que tiene una fotografia_id invalida.
        $carrito = [[
            'fotografia_id' => 9999999,
            'formato_id'    => $formato->id,
            'soporte_id'    => $soporte->id,
            'precio'        => 5.00,
            'cantidad'      => 1,
        ]];

        $response = $this->withoutMiddleware()
            ->actingAs($user)
            ->withSession(['Carrito' => $carrito])
            ->post(route('procesarcarrito'));

        $response->assertStatus(200);
        $response->assertViewIs('errores.error');

        // La transacción debe haberse revertido: no debe quedar ningún pedido del usuario
        $this->assertDatabaseMissing('pedidos', ['user_id' => $user->id]);

        // Ni ningún item con esa fotografia_id inválida
        $this->assertDatabaseMissing('items', ['fotografia_id' => 99999]);
    }

}
