<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reportaje;
use App\Models\Role;
use App\Models\User;
use App\Enums\NombreRole;
use App\Enums\TipoReportaje;
use App\Models\Pedido;
use App\Enums\EstadoPedido;
use App\Models\Etiqueta;

 /**
     * Controlador de la zona privada de los distintos usuarios.
     *
     * Proporciona vistas y entrega a estas datos necesarios para el panel privado de un usuario
     * autenticado: reportajes, citas, pedidos y datos auxiliares (enums, etiquetas...).
     */

class ZonaPrivadaController extends Controller
{
   
    /**
     * Carga los datos necesarios para la vista de la zona privada del usuario autenticado.
     *
     * Reúne reportajes, citas, pedidos, enums y otras listas auxiliares y
     * retorna la vista 'zonaprivada.privada' con dichos datos.
     * La vista se adapta según el rol del usuario (cliente, empleado, admin) mostrando solo lo relevante
     * y para cada uno.
     *
     * @return \Illuminate\View\View
     */
    public function cargarZonaPrivada()
    {
       
        $id = Auth::user()->id;
        $reportajes = Reportaje::where('user_id', $id)->get();
        $citas = Cita::where('user_id', $id)->get();        
        $usuario = User::find($id);
        // Usar los enums nativos para valores fijos en BD
        $roles = NombreRole::values();
        $tiposRepor = TipoReportaje::values();
        $pedidos = Pedido::where('user_id', $id)->get();
        $estadosPedido = EstadoPedido::values();
        $nombresEtiquetas = \App\Models\Etiqueta::pluck('nombre_etiqueta')->toArray();
        $estadosCita = \App\Enums\EstadoCita::values();
        $turnosCita = \App\Enums\TurnoCita::values();

        return view('zonaprivada.privada', [
            'reportajes' => $reportajes,
            'roles' => $roles,
            'usuario' => $usuario,
            'citas' => $citas,
            'tiposRepor' => $tiposRepor,
            'pedidos' => $pedidos,
            'estadosPedido' => $estadosPedido,
            'nombresEtiquetas' => $nombresEtiquetas,
            'estadosCita' => $estadosCita,
            'turnosCita' => $turnosCita,
        ]);
    }
}
