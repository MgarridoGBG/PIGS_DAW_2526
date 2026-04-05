<?php

namespace Tests\Feature;

use App\Enums\EstadoPedido;
use App\Enums\NombreRole;
use App\Http\Controllers\PedidoController;
use App\Models\Formato;
use App\Models\Item;
use App\Models\Pedido;
use App\Models\Role;
use App\Models\Soporte;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PruebasPedidoTest extends TestCase
{
    use RefreshDatabase;

    // Helper privado para crear usuario con role específico

    private function crearUsuarioConRole(string $nombreRole): User
    {
        $role = Role::factory()->create(['nombre_role' => $nombreRole]);

        return User::factory()->create(['role_id' => $role->id]);
    }

    // calcularPrecioPedido

    /**
     * Calcula correctamente el precio total sumando precio * cantidad de cada item.
     */
    public function test_calcula_precio_pedido_bien(): void
    {
        $pedido = Pedido::factory()->create();

        // Item 1: precio=10, cantidad=2 → subtotal=20
        Item::factory()->create(['pedido_id' => $pedido->id, 'precio' => 10.00, 'cantidad' => 2]);
        // Item 2: precio=5.50, cantidad=4 → subtotal=22
        Item::factory()->create(['pedido_id' => $pedido->id, 'precio' => 5.50, 'cantidad' => 4]);

        $controlador = new PedidoController;
        $resultado = $controlador->calcularPrecioPedido($pedido->id);

        $this->assertSame(42.00, $resultado);
    }

    // filtrarPedidos

    /**
     * Filtrar por id devuelve solo el pedido con ese identificador.
     */
    public function test_filtrar_pedidos_por_id(): void
    {
        $pedidoBuscado = Pedido::factory()->create();
        Pedido::factory()->count(3)->create();

        $response = $this->withoutMiddleware()
            ->get(route('filtrarpedidos', ['identificacion' => $pedidoBuscado->id]));

        $response->assertStatus(200);
        $response->assertViewIs('parciales.listados.listarpedidos');
        $this->assertSame(1, $response->viewData('pedidos')->total());
    }

    /**
     * Filtrar por email_usuario devuelve solo los pedidos de ese usuario.
     */
    public function test_filtrar_pedidos_email_user(): void
    {
        $usuarioA = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);
        $usuarioB = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);

        Pedido::factory()->count(2)->create(['user_id' => $usuarioA->id]);
        Pedido::factory()->count(5)->create(['user_id' => $usuarioB->id]);

        $response = $this->withoutMiddleware()
            ->get(route('filtrarpedidos', ['email_usuario' => $usuarioA->email]));

        $response->assertStatus(200);
        $this->assertSame(2, $response->viewData('pedidos')->total());
    }

    // procesarFormEditarPedido

    /**
     * email_usuario con formato inválido falla la validación.
     */
    public function test_fallo_email_invalido(): void
    {
        $pedido = Pedido::factory()->create();

        $response = $this->withoutMiddleware()
            ->put(route('editarpedido', $pedido->id), [
                'email_usuario' => 'no-es-un-email',
            ]);

        $response->assertSessionHasErrors(['email_usuario']);
    }

    /**
     * email_usuario con formato correcto pero no existente en BD falla la validación.
     */
    public function test_fallo_email_no_existe(): void
    {
        $pedido = Pedido::factory()->create();

        $response = $this->withoutMiddleware()
            ->put(route('editarpedido', $pedido->id), [
                'email_usuario' => 'noexiste@ejemplo.com',
            ]);

        $response->assertSessionHasErrors(['email_usuario']);
    }

    /**
     * estado_pedido fuera del enum falla la validación.
     */
    public function test_fallo_estado_invalido(): void
    {
        $pedido = Pedido::factory()->create();

        $response = $this->withoutMiddleware()
            ->put(route('editarpedido', $pedido->id), [
                'estado_pedido' => 'estado_inventado',
            ]);

        $response->assertSessionHasErrors(['estado_pedido']);
    }

    /**
     * fecha_pedido con valor no-fecha falla la validación.
     */
    public function test_fallo_fecha_invalida(): void
    {
        $pedido = Pedido::factory()->create();

        $response = $this->withoutMiddleware()
            ->put(route('editarpedido', $pedido->id), [
                'fecha_pedido' => 'no-es-una-fecha',
            ]);

        $response->assertSessionHasErrors(['fecha_pedido']);
    }

    /**
     * Con datos válidos la transacción actualiza el pedido correctamente.
     */
    public function test_editar_pedido_datos_validos(): void
    {
        $pedido = Pedido::factory()->create(['estado_pedido' => EstadoPedido::EMITIDO->value]);
        $nuevoUsuario = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);

        $response = $this->withoutMiddleware()
            ->put(route('editarpedido', $pedido->id), [
                'email_usuario' => $nuevoUsuario->email,
                'estado_pedido' => EstadoPedido::PAGADO->value,
                'fecha_pedido' => '2026-06-01',
            ]);

        $response->assertViewIs('errores.exito');
        $this->assertDatabaseHas('pedidos', [
            'id' => $pedido->id,
            'user_id' => $nuevoUsuario->id,
            'estado_pedido' => EstadoPedido::PAGADO->value,
            'fecha_pedido' => '2026-06-01',
        ]);
    }

    /**
     * Si algún item del carrito no se puede añadir (tiene fotografia_id inválida), la transacción
     * hace rollback y el pedido no queda modificado.
     */
    public function test_fallo_editar_pedido_item_invalido(): void
    {
        $pedido = Pedido::factory()->create(['estado_pedido' => EstadoPedido::EMITIDO->value]);
        $estadoOriginal = $pedido->estado_pedido;

        $formato = Formato::factory()->create();
        $soporte = Soporte::factory()->create();

        // Carrito con fotografia_id inexistente → FK constraint fallará en SQLite
        $carritoInvalido = [[
            'fotografia_id' => 99999,
            'formato_id' => $formato->id,
            'soporte_id' => $soporte->id,
            'precio' => 10.00,
            'cantidad' => 1,
        ]];

        $response = $this->withoutMiddleware()
            ->withSession(['Carrito' => $carritoInvalido])
            ->put(route('editarpedido', $pedido->id), [
                'estado_pedido' => EstadoPedido::PAGADO->value,
            ]);

        $response->assertStatus(200);
        $response->assertViewIs('errores.error');

        // El estado del pedido no debe haber cambiado
        $this->assertDatabaseHas('pedidos', [
            'id' => $pedido->id,
            'estado_pedido' => $estadoOriginal,
        ]);
    }
}
