<?php

namespace App\Http\Controllers;

use App\Models\Formato;
use App\Models\Fotografia;
use App\Models\Item;
use App\Models\Pedido;
use App\Models\Soporte;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Controlador para gestionar el carrito de la compra en sesión.
 */
class CarritoController extends Controller
{
    /**
     * Muestra el formulario para añadir un nuevo item al carrito
     * con la ID de una fotografía.
     */
    public function mostrarFormCarrito($id)
    {
        $formatos = Formato::pluck('nombre_format');

        $soportes = Soporte::where('disponibilidad', true)->get(); // Solo soportes disponibles

        $fotografia = Fotografia::with('reportaje')->find($id);
        $nombreFotografia = $fotografia ? $fotografia->nombre_foto : null;
        $reportaje = $fotografia ? $fotografia->reportaje : null;

        return view('administracion.formularios.formnuevoitemcarrito', [
            'fotografia_id' => $id,
            'nombre_fotografia' => $nombreFotografia,
            'reportaje' => $reportaje,
            'formatos' => $formatos,
            'soportes' => $soportes,
        ]);
    }

    /**
     * Añade un nuevo item al carrito almacenado en sesión.
     *
     * Recibe y valida los campos del formulario anterior
     * y guarda el item en la sesión bajo el nombre 'Carrito'.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function registrarNuevoItemCarrito(Request $request)
    {
        // datos del form
        $fotografiaId = $request->input('fotografia_id');
        $nombreFormato = $request->input('formato');
        $nombreSoporte = $request->input('soporte');

        $cantidad = (int) $request->input('cantidad', 1); // Por defecto 1
        if ($cantidad < 1) {
            return view('errores.error', ['mensaje' => 'Cantidad inválida.']);
        }

        // Buscar IDs de formato y soporte por nombre
        $formato = Formato::where('nombre_format', $nombreFormato)->first();
        $soporte = Soporte::where('nombre_soport', $nombreSoporte)->first();

        if (! $formato || ! $soporte) {
            return view('errores.error', ['mensaje' => 'Error al añadir el artículo.']);
        }

        // Calcular el precio del item usando el método del ItemController
        $itemController = new ItemController;
        $precio = $itemController->calcularPrecio($formato->id, $soporte->id);

        // Crear el item para añadir a sesión
        $nuevoItem = [
            'fotografia_id' => $fotografiaId,
            'formato_id' => $formato->id,
            'soporte_id' => $soporte->id,
            'precio' => $precio,
            'cantidad' => $cantidad,
        ];

        // Comprobar si existe Carrito en sesión
        $carrito = session('Carrito', []);

        if (count($carrito) >= 15) { // Límite de 15 items por pedido
            return view('errores.error', ['mensaje' => 'Ha alcanzado el número máximo de artículos por pedido.']);
        }

        // Añadir el nuevo item al array guardar en sesion y recuperar los datos para mostrar
        $carrito[] = $nuevoItem;

        session(['Carrito' => $carrito]);

        $items = [];
        foreach ($carrito as $item) {
            $fotografia = Fotografia::find($item['fotografia_id']);
            $formatoItem = Formato::find($item['formato_id']);
            $soporteItem = Soporte::find($item['soporte_id']);
            $reportajeCodigo = null;
            if ($fotografia && $fotografia->reportaje) {
                $reportajeCodigo = $fotografia->reportaje->codigo;
            }

            $items[] = [
                'nombre_fotografia' => $fotografia ? $fotografia->nombre_foto : 'N/A',
                'nombre_formato' => $formatoItem ? $formatoItem->nombre_format : 'N/A',
                'nombre_soporte' => $soporteItem ? $soporteItem->nombre_soport : 'N/A',
                'reportaje_codigo' => $reportajeCodigo ?? 'N/A',
                'precio' => $item['precio'] ?? 0.00,
                'cantidad' => $item['cantidad'] ?? 1,
                'precio_total' => round(($item['precio'] ?? 0.00) * ($item['cantidad'] ?? 1), 2),
            ];
        }

        // Calcular precio total del carrito y devuelve la vista
        $precioTotal = $this->calcularPrecio();

        return view('parciales.itemanadidocarrito', ['items' => $items, 'precioTotal' => $precioTotal]);
    }

    /**
     * Muestra la vista completa del carrito a partir de los items guardados
     * en sesión y calcula el precio total.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function MostrarCarrito(Request $request)
    {

        $carrito = session('Carrito', []);

        // Recuperar datos para mostrar
        $items = [];
        foreach ($carrito as $item) {
            $fotografia = Fotografia::find($item['fotografia_id']);
            $formatoItem = Formato::find($item['formato_id']);
            $soporteItem = Soporte::find($item['soporte_id']);
            $reportajeCodigo = null;
            if ($fotografia && $fotografia->reportaje) {
                $reportajeCodigo = $fotografia->reportaje->codigo;
            }

            $items[] = [
                'nombre_fotografia' => $fotografia ? $fotografia->nombre_foto : 'N/A',
                'nombre_formato' => $formatoItem ? $formatoItem->nombre_format : 'N/A',
                'nombre_soporte' => $soporteItem ? $soporteItem->nombre_soport : 'N/A',
                'reportaje_codigo' => $reportajeCodigo ?? 'N/A',
                'precio' => $item['precio'] ?? 0.00,
                'cantidad' => $item['cantidad'] ?? 1,
                'precio_total' => round(($item['precio'] ?? 0.00) * ($item['cantidad'] ?? 1), 2),
            ];
        }

        // Calcular precio total del carrito y devuelve la vista
        $precioTotal = $this->calcularPrecio();

        return view('parciales.vercarrito', ['items' => $items, 'precioTotal' => $precioTotal]);
    }

    /**
     * Elimina un item del carrito por su índice ('item_index') y actualiza la
     * sesión. Recalcula y devuelve vista parcial con los otros items.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function borrarItemCarrito(Request $request)
    {
        // Obtener el índice del item a eliminar
        $numeroItem = $request->input('item_index');

        // Abrir el Carrito en sesión, verificar que el índice existe y eliminar el item
        $carrito = session('Carrito', []);

        if (isset($carrito[$numeroItem])) {
            unset($carrito[$numeroItem]);
            $carrito = array_values($carrito); // Reindexar
        }

        // Guardar el carrito
        session(['Carrito' => $carrito]);

        // Recuperar datos, calcular precio y mostrar la vista.
        $items = [];
        foreach ($carrito as $item) {
            $fotografia = Fotografia::find($item['fotografia_id']);
            $formatoItem = Formato::find($item['formato_id']);
            $soporteItem = Soporte::find($item['soporte_id']);
            $reportajeCodigo = null;
            if ($fotografia && $fotografia->reportaje) {
                $reportajeCodigo = $fotografia->reportaje->codigo;
            }

            $items[] = [
                'nombre_fotografia' => $fotografia ? $fotografia->nombre_foto : 'N/A',
                'nombre_formato' => $formatoItem ? $formatoItem->nombre_format : 'N/A',
                'nombre_soporte' => $soporteItem ? $soporteItem->nombre_soport : 'N/A',
                'reportaje_codigo' => $reportajeCodigo ?? 'N/A',
                'precio' => $item['precio'] ?? 0.00,
                'cantidad' => $item['cantidad'] ?? 1,
                'precio_total' => round(($item['precio'] ?? 0.00) * ($item['cantidad'] ?? 1), 2),
            ];
        }

        $precioTotal = $this->calcularPrecio();

        return view('parciales.itemeliminadocarrito', ['items' => $items, 'precioTotal' => $precioTotal]);
    }

    /**
     * Vacía el carrito completo eliminando la clave 'Carrito' de la sesión.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function vaciarCarrito(Request $request)
    {

        session()->forget('Carrito');

        return view('parciales.vercarrito', ['items' => [], 'precioTotal' => 0]);
    }

    /**
     * Procesa el carrito convirtiéndolo en un 'Pedido' en base de
     * datos. Se realiza dentro de una transacción para garantizar atomicidad:
     * - Crea el 'Pedido' principal con 'user_id', 'estado_pedido' y 'fecha_pedido'.
     * - Crea los 'Item' asociados al pedido.
     * - Si todo va bien, confirma la transacción y vacía la
     *   sesión; en caso contrario, revierte y muestra un error.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function procesarCarrito(Request $request)
    {
        $carrito = session('Carrito', []);

        // Si el carrito está vacío, error al canto.
        if (empty($carrito)) {
            return view('errores.error', ['mensaje' => 'Su carrito está vacío']);
        }

        // Usar transacción para asegurar atomicidad
        DB::beginTransaction();

        // Intentar crear el pedido y los items, si falla algo, se revierte todo
        try {

            // Crear pedido
            $pedido = new Pedido;
            $pedido->user_id = Auth::id();
            $pedido->estado_pedido = 'emitido';
            $pedido->fecha_pedido = Carbon::now();

            if (! $pedido->save()) {
                DB::rollBack();

                return view('errores.error', ['mensaje' => 'Pedido no emitido']);
            }

            // Crear items del pedido
            foreach ($carrito as $itemCarrito) {
                $item = new Item;
                $item->pedido_id = $pedido->id;
                $item->fotografia_id = $itemCarrito['fotografia_id'];
                $item->formato_id = $itemCarrito['formato_id'];
                $item->soporte_id = $itemCarrito['soporte_id'];
                $item->precio = $itemCarrito['precio'];
                $item->cantidad = $itemCarrito['cantidad'] ?? 1;

                // Si falla al guardar algún item, deshacer todo
                if (! $item->save()) {
                    DB::rollBack();

                    return view('errores.error', ['mensaje' => 'Pedido no emitido']);
                }
            }

            // Si todo está bien, confirmar transacción
            DB::commit();

            // Vaciar el carrito y devolver vista
            session()->forget('Carrito');

            return view('errores.exito', ['mensaje' => 'Gracias, pedido registrado']);
        } catch (\Exception $e) {

            // En caso de cualquier error, deshacer transacción
            DB::rollBack();

            return view('errores.error', ['mensaje' => 'Pedido no emitido']);
        }
    }

    /**
     * Calcula el precio total del carrito en sesión.
     *
     * @return float
     */
    public function calcularPrecio()
    {

        $carrito = session('Carrito', []);

        $precioTotal = 0;

        // Recorrer todos los items del carrito obtener el de cada y sumar el total
        foreach ($carrito as $item) {
            $precio = $item['precio'] ?? 0;
            $cantidad = $item['cantidad'] ?? 1;

            $precioTotal += $precio * $cantidad;
        }

        // Devolver el total redondeado a dos decimales
        return round($precioTotal, 2);
    }
}
