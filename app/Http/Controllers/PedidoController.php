<?php

namespace App\Http\Controllers;

use App\Enums\EstadoPedido;
use App\Models\Item;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Controlador de Pedidos
 *
 * Mostrar, listar, filtrar, modificar y borrar pedidos,
 * así como de calcular totales y detectar pedidos "fantasma" (sin items).
 */
class PedidoController extends Controller
{
    /**
     * Muestra el detalle de un pedido con sus items y calcula totales.
     *
     * @param  int  $id  ID del pedido
     * @return \Illuminate\View\View Vista parcial con los datos del pedido o vista de error
     */
    public function verDetallePedido($id)
    {
        // Carga el pedido por su id y el role de su user y verifica si existe
        $pedido = Pedido::with('user.role')->find($id);
        if (! $pedido) {
            return view('errores.error', [
                'mensaje' => 'Pedido no encontrado',
            ]);
        }

        // Verificar que el pedido pertenece al usuario autenticado, o el usuario es admin/empleado
        $userRole = Auth::check() && Auth::user()->role ? Auth::user()->role->nombre_role : null;
        $userEsAdminOEmpleado = in_array($userRole, ['admin', 'empleado'], true);
        if (! ($pedido->user_id == Auth::id() || $userEsAdminOEmpleado)) {
            return view('errores.error', [
                'mensaje' => 'No tiene permiso para ver este pedido',
            ]);
        }

        // Obtener los items del pedido con sus relaciones
        $items = $pedido->items()->with(['fotografia', 'formato', 'soporte'])->get();

        // Calcular precio_total por item según su cantidad
        $items = $items->map(function ($item) {
            $cantidad = $item->cantidad ?? 1;
            $item->precio_total = round(($item->precio ?? 0) * $cantidad, 2);

            return $item;
        });

        $precioPedido = $this->calcularPrecioPedido($pedido->id);

        // Pasamos ambas variables a la vista
        return view('parciales.pedidodetallado', [
            'pedido' => $pedido,
            'items' => $items,
            'precioPedido' => $precioPedido,
        ]);
    }

    /**
     * Elimina un pedido por su ID.
     *
     * @param  int  $id  ID del pedido a eliminar
     * @return \Illuminate\View\View
     */
    public function borrarPedido($id)
    {
        $pedido = Pedido::find($id);

        if ($pedido) {
            $eliminado = $pedido->delete();
            if ($eliminado) {
                return view('errores.exito', [
                    'mensaje' => "Pedido con ID:{$id} eliminado correctamente.",
                ]);
            }
        }

        return view('errores.error', ['mensaje' => "No se ha podido eliminar el pedido con ID:{$id}"]);
    }

    /**
     * Lista pedidos paginados.
     *
     * @return \Illuminate\View\View
     */
    public function listarPedidos()
    {
        $pedidos = Pedido::paginate(15);
        $mensaje = 'Encontrados '.$pedidos->total().' pedidos en la base de datos';

        return view('parciales.listados.listarpedidos', compact('pedidos', 'mensaje'));
    }

    /**
     * Calcula el precio total de un pedido sumando el precio*cantidad
     * de cada item.
     *
     * @param  int  $pedido_id  ID del pedido
     * @return float Precio total redondeado a 2 decimales
     */
    public function calcularPrecioPedido($pedido_id)
    {
        // Obtener el pedido con sus items
        $pedido = Pedido::with('items')->find($pedido_id);

        if (! $pedido) {
            return 0;
        }

        // Inicializar el precio del pedido y recorremos los items sumando los precios de cada uno
        $precioPedido = 0;
        foreach ($pedido->items as $item) {
            $precio = $item->precio ?? 0;
            $cantidad = $item->cantidad ?? 1;
            $precioTotal = $precio * $cantidad;

            $precioPedido += $precioTotal;
        }

        return round($precioPedido, 2);
    }

    /**
     * Filtra la lista de pedidos según parámetros de la petición.
     *
     * @return \Illuminate\View\View
     */
    public function filtrarPedidos(Request $peticion)
    {
        // Aplicamos los filtros y devolvemos la lista (aceptando GET y POST)
        $pedidos = Pedido::query()
            ->when($peticion->filled('identificacion'), function ($consulta) use ($peticion) {
                return $consulta->where('id', $peticion->identificacion);
            })
            ->when($peticion->filled('user_id'), function ($consulta) use ($peticion) {
                return $consulta->where('user_id', $peticion->user_id);
            })
            ->when($peticion->filled('email_usuario'), function ($consulta) use ($peticion) {
                return $consulta->whereHas('user', function ($subConsulta) use ($peticion) {
                    $subConsulta->where('email', 'like', '%'.$peticion->email_usuario.'%');
                });
            })
            ->when($peticion->filled('estadoPedido'), function ($consulta) use ($peticion) {
                return $consulta->where('estado_pedido', $peticion->estadoPedido);
            })
            ->when($peticion->filled('fecha_pedido'), function ($consulta) use ($peticion) {
                return $consulta->where('fecha_pedido', $peticion->fecha_pedido);
            })
            ->paginate(15)
            ->appends($peticion->except('page'));

        $mensaje = 'Encontrados '.$pedidos->total().' pedidos en la base de datos según los filtros aplicados';

        return view('parciales.listados.listarpedidos', compact('pedidos', 'mensaje'));
    }

    /**
     * Muestra el formulario para editar un pedido.
     *
     * @param  int  $id  ID del pedido
     * @return \Illuminate\View\View
     */
    public function mostrarFormEditarPedido($id)
    {
        $pedido = Pedido::findOrFail($id);
        // Usar enum nativo para valores estables de rol
        $estados = EstadoPedido::values();

        return view('administracion.formularios.formeditarpedido', [
            'pedido' => $pedido,
            'estados' => $estados,
        ]);
    }

    /**
     * Procesa el formulario de edición de un pedido.
     *
     * Valida la entrada, actualiza campos opcionales, añade items desde
     * la sesión si existen y realiza los cambios con una transacción.
     *
     * @param  int  $id  ID del pedido a modificar
     * @return \Illuminate\View\View
     */
    public function procesarFormEditarPedido(Request $peticion, $id)
    {
        $pedido = Pedido::findOrFail($id);

        // Validar datos del formulario
        $datosValidados = $peticion->validate([
            'email_usuario' => 'nullable|email|exists:users,email',
            'estado_pedido' => 'nullable|in:'.implode(',', EstadoPedido::values()),
            'fecha_pedido' => 'nullable|date',
        ], [
            'email_usuario.email' => 'El campo email debe ser una dirección de correo válida.',
            'email_usuario.exists' => 'No existe ningún usuario con ese email.',

        ]);

        // Transacción para asegurar integridad al modificar el pedido y añadir items
        try {
            DB::beginTransaction();

            // Actualizar solo los campos que tienen datos
            if ($peticion->filled('email_usuario')) {
                $usuario = User::where('email', $datosValidados['email_usuario'])->first();
                if ($usuario) {
                    $pedido->user_id = $usuario->id;
                }
            }
            if ($peticion->filled('estado_pedido')) {
                $pedido->estado_pedido = $datosValidados['estado_pedido'];
            }
            if ($peticion->filled('fecha_pedido')) {
                $pedido->fecha_pedido = $datosValidados['fecha_pedido'];
            }

            // Añadir items del carrito si existen en sesión
            $carrito = session('Carrito', []);
            if (! empty($carrito)) {
                foreach ($carrito as $itemCarrito) {
                    $item = new Item;
                    $item->pedido_id = $pedido->id;
                    $item->fotografia_id = $itemCarrito['fotografia_id'] ?? null;
                    $item->formato_id = $itemCarrito['formato_id'] ?? null;
                    $item->soporte_id = $itemCarrito['soporte_id'] ?? null;
                    $item->precio = $itemCarrito['precio'] ?? 0;
                    $item->cantidad = $itemCarrito['cantidad'] ?? 1;

                    if (! $item->save()) {
                        DB::rollBack();

                        return view('errores.error', ['mensaje' => 'Se han producido errores al añadir items']);
                    }
                }

                session()->forget('Carrito');
            }

            // Guardar los cambios
            $guardado = $pedido->save();

            if ($guardado) {
                DB::commit();

                return view('errores.exito', ['mensaje' => "Pedido con ID {$id} correctamente modificado"]);
            } else {
                DB::rollBack();

                return view('errores.error', ['mensaje' => 'Error en la modificación de datos']);
            }
        } catch (\Exception $excepcion) {
            DB::rollBack();

            return view('errores.error', ['mensaje' => "No se ha podido modificar el pedido con ID {$id}"]);
        }
    }

    /**
     * Busca y lista pedidos sin items asociados ("pedidos fantasma").
     *
     * @return \Illuminate\View\View
     */
    public function buscarPedidosFantasma()
    {
        $pedidos = Pedido::doesntHave('items')->paginate(15);

        $total = $pedidos->total();
        $mensaje = $total > 0 ? "Pedidos fantasma encontrados: {$total}" : 'No se han encontrado pedidos fantasma.';

        return view('parciales.listados.listarpedidos', compact('pedidos', 'mensaje'));
    }
}
