<?php

namespace App\Http\Controllers;

use App\Models\Formato;
use App\Models\Item;
use App\Models\Soporte;

/**
 * Controlador de Items
 *
 * Gestión de items dentro de un pedido y cálculo de precios.
 */
class ItemController extends Controller
{
    /**
     * Calcular precio de un item basado en formato y soporte
     *
     * @param  int  $formato_id  ID del formato
     * @param  int  $soporte_id  ID del soporte
     * @return float Precio calculado con dos decimales
     */
    public function calcularPrecio($formato_id, $soporte_id)
    {
        // Obtener el formato y soporte por ID y verificar que existen
        $formato = Formato::find($formato_id);
        $soporte = Soporte::find($soporte_id);
        if (! $formato || ! $soporte) {
            return 0.00;
        }

        // Calcular precio redondeado: ((alto * ancho) * precio_soporte) / 10000
        $precioItem = (($formato->alto * $formato->ancho) * $soporte->precio) / 10000;

        return round($precioItem, 2);
    }

    /**
     * Elimina un Item de un Pedido y devuelve la vista
     * parcial actualizada del pedido.
     *
     * @param  int  $id  ID del item a eliminar
     * @return \Illuminate\View\View Vista parcial del pedido o vista de error
     */
    public function borrarItemPedido($id)
    {
        // Buscar el item por ID
        $item = Item::find($id);

        if ($item) {
            // Guardar el pedido_id antes de eliminar el item
            $pedidoId = $item->pedido_id;

            $eliminado = $item->delete();
            if ($eliminado) {
                // Actualizar la vista del pedido después de eliminar el item
                $pedido = \App\Models\Pedido::with('user')->find($pedidoId);
                $items = $pedido->items()->with(['fotografia', 'formato', 'soporte'])->get();

                // Calcular precio_total por item según su cantidad
                $items = $items->map(function ($item) {
                    $cantidad = $item->cantidad ?? 1;
                    $item->precio_total = round(($item->precio ?? 0) * $cantidad, 2);

                    return $item;
                });

                $pedidoController = new PedidoController;
                $precioPedido = $pedidoController->calcularPrecioPedido($pedidoId);

                return view('parciales.pedidodetallado', [
                    'pedido' => $pedido,
                    'items' => $items,
                    'precioPedido' => $precioPedido,
                ]);
            }
        }

        return view('errores.error', ['mensaje' => "No se ha podido eliminar el item con ID:{$id}"]);
    }
}
