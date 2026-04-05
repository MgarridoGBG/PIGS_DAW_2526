<?php

namespace App\Http\Controllers;

use App\Enums\EstadoCita;
use App\Enums\TurnoCita;
use App\Models\Cita;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador para gestionar las citas (reservas) del sistema.
 *
 * Proporciona operaciones para listar, filtrar, borrar, mostrar y procesar
 * formularios de edición de citas.
 * Las citas se crean a traves de la vista de calendario y con el controlador API CitasCalendarController,
 * pero este controlador permite editarlas o borrarlas posteriormente.
 */
class CitaController extends Controller
{
    /**
     * Lista las citas existentes paginadas.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function listarCitas()
    {
        $citas = Cita::with('user')->paginate(15); // Eager loading de la relación 'user' cargando los usuarios asociados a las citas de antemano
        $mensaje = 'Encontradas '.$citas->total().' citas en la base de datos';

        return view('parciales.listados.listacitas', compact('citas', 'mensaje'));
    }

    /**
     * Filtra las citas según parámetros opcionales y devuelve una lista paginada.
     * soporta GET y POST por necesidad en la paginación.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function filtrarCitas(Request $peticion)
    {
        // Aplicamos los filtros y devolvemos la lista (aceptando GET y POST)
        $citas = Cita::with('user')
            ->when($peticion->filled('identificacion'), function ($consulta) use ($peticion) {
                return $consulta->where('id', $peticion->identificacion);
            })
            ->when($peticion->filled('user_id'), function ($consulta) use ($peticion) {
                return $consulta->where('user_id', $peticion->user_id);
            })
            ->when($peticion->filled('email_usuario'), function ($consulta) use ($peticion) {
                return $consulta->whereHas('user', function ($consultar) use ($peticion) {
                    $consultar->where('email', 'like', '%'.$peticion->email_usuario.'%');
                });
            })
            ->when($peticion->filled('estadoCita'), function ($consulta) use ($peticion) {
                return $consulta->where('estado_cita', 'like', '%'.$peticion->estadoCita.'%');
            })
            ->when($peticion->filled('turno'), function ($consulta) use ($peticion) {
                return $consulta->where('turno', 'like', '%'.$peticion->turno.'%');
            })
            ->when($peticion->filled('fecha_cita'), function ($consulta) use ($peticion) {
                return $consulta->where('fecha_cita', $peticion->fecha_cita);
            })
            ->paginate(15)
            ->appends($peticion->except('page')); // Mantener los parámetros de filtro en la paginación

        $mensaje = 'Encontradas '.$citas->total().' citas en la base de datos según los filtros aplicados';

        return view('parciales.listados.listacitas', compact('citas', 'mensaje'));
    }

    /**
     * Elimina una cita por su identificador.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function borrarCita($id)
    {
        $cita = Cita::find($id);

        if ($cita) {
            $eliminado = $cita->delete();
            if ($eliminado) {
                return view('errores.exito', ['mensaje' => "Cita con ID {$id} eliminada con exito"]);
            }
        }

        return view('errores.error', ['mensaje' => "No se ha podido eliminar la cita {$id}"]);
    }

    /**
     * Elimina una cita propia por su identificador.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function borrarCitaPropia()
    {
        $cita = Cita::where('user_id', Auth::id())->first();

        if ($cita) {
            $eliminado = $cita->delete();
            if ($eliminado) {
                return view('errores.exito', ['mensaje' => "Su cita con ID {$cita->id} eliminada con exito"]);
            }
        }

        return view('errores.error', ['mensaje' => "No se ha podido eliminar su cita {$cita->id}"]);
    }

    /**
     * Muestra el formulario de edición para una cita concreta.
     *
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function mostrarFormEditarCita($id)
    {
        $cita = Cita::findOrFail($id);
        $estados = EstadoCita::values();
        $turnos = TurnoCita::values();

        return view('administracion.formularios.formeditarcita', [
            'cita' => $cita,
            'estados' => $estados,
            'turnos' => $turnos,
        ]);
    }

    /**
     * Procesa el formulario de edición de una cita.
     *
     * Valida los campos recibidos y actualiza únicamente los atributos presentes en la
     * petición. Se espera una petición de tipo PUT/POST; las GET devuelven
     * una vista de error.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function procesarFormEditarCita(Request $peticion, $id)
    {

        if ($peticion->isMethod('get')) {
            return view('errores.error', ['mensaje' => 'Acceso denegado Vuelva a la página anterior y utiliza el formulario de búsqueda.']);
        }

        $cita = Cita::findOrFail($id);

        // Validar datos del formulario
        $datosValidados = $peticion->validate([
            'email_usuario' => 'nullable|email|exists:users,email',
            'turno' => 'nullable|in:'.implode(',', TurnoCita::values()),
            'fecha_cita' => 'nullable|date',
            'estado_cita' => 'nullable|in:'.implode(',', EstadoCita::values()),
        ], [
            'email_usuario.email' => 'El campo email debe ser una dirección de correo válida.',
            'email_usuario.exists' => 'No existe ningún usuario con ese email.',
            'turno.in' => 'El turno seleccionado no es válido.',
            'estado_cita.in' => 'El estado de cita seleccionado no es válido.',
            'fecha_cita.date' => 'La fecha debe ser una fecha válida.',
        ]);

        // Actualizar solo los campos que tienen datos
        if ($peticion->filled('email_usuario')) {
            $usuario = User::where('email', $datosValidados['email_usuario'])->first();
            if ($usuario) {
                $cita->user_id = $usuario->id;
            }
        }

        if ($peticion->filled('turno')) {
            $cita->turno = $datosValidados['turno'];
        }

        if ($peticion->filled('fecha_cita')) {
            $cita->fecha_cita = $datosValidados['fecha_cita'];
        }

        if ($peticion->filled('estado_cita')) {
            $cita->estado_cita = $datosValidados['estado_cita'];
        }

        // Guardar los cambios. Los errores de integridad referencial (por ejemplo, fecha/turno ya ocupados) se capturan
        // y se muestra con un mensaje propio en lugar del global de error de base de datos.
        try {
            $guardado = $cita->save();

            if ($guardado) {
                return view('errores.exito', ['mensaje' => "Cita con ID {$id} correctamente modificada"]);
            } else {
                return view('errores.error', ['mensaje' => "No se ha podido modificar la cita con ID {$id}"]);
            }
        } catch (QueryException $e) {
            return view('errores.error', ['mensaje' => 'Error en la modificación de datos. Puede que la fecha y turno ya estén ocupados por otra cita o que el usuario ya tenga una cita.']);
        }
    }
}
