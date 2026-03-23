<?php

namespace App\Http\Controllers;

use App\Models\Soporte;

use Illuminate\Http\Request;

/**
 * Controlador de Soportes
 * 
 * Gestión de soportes: listado, filtrado, creación, edición y eliminación.  
 */

class SoporteController extends Controller
{

    /**
     * Lista los soportes paginados.
     *
     * @return \Illuminate\View\View
     */
    public function listarSoportes()
    {
        $soportes = Soporte::paginate(15);
        $mensaje = "Encontrados " . $soportes->total() . " soportes en la base de datos";
        return view('parciales.listados.listarsoportes', compact('soportes', 'mensaje'));
    }

    /**
     * Muestra el formulario para editar un soporte por su ID.
     *
     * @param int $id ID del soporte
     * @return \Illuminate\View\View
     */
    public function mostrarFormEditarSoporte($id)
    {
        $soporte = Soporte::findOrFail($id);

        return view('administracion.formularios.formeditarsoporte', [
            'soporte' => $soporte
        ]);
    }

    /**
     * Elimina un soporte por su ID.
     *
     * @param int $id ID del soporte a eliminar
     * @return \Illuminate\View\View
     */
    public function borrarSoporte($id)
    {
        $soporte = Soporte::find($id);

        if ($soporte) {
            $eliminado = $soporte->delete();
            if ($eliminado) {
                return view('errores.exito', ['mensaje' => "Soporte {$soporte->nombre_soport} eliminado con exito"]);
            }
        }

        return view('errores.error', ['mensaje' => "No se ha podido eliminar el soporte con ID:{$id}"]);
    }

    /**
     * Procesa la edición de un soporte.
     *
     * Valida la petición y actualiza únicamente los campos proporcionados.
     *
     * @param \Illuminate\Http\Request $peticion
     * @param int $id ID del soporte
     * @return \Illuminate\View\View
     */
    public function procesarFormEditarSoporte(Request $peticion, $id)
    {
        // Si la petición es GET mostramos la vista de error solicitada
        if ($peticion->isMethod('get')) {
            return view('errores.error', ['mensaje' => 'Acceso denegado Vuelva a la página anterior y utiliza el formulario de búsqueda.']);
        }
        $soporte = Soporte::findOrFail($id);

        // Validar los datos recibidos
        $datosValidados = $peticion->validate([
            'nombre_soport' => 'nullable|string|max:50|unique:soportes,nombre_soport',
            'disponibilidad' => 'nullable|boolean',
            'precio' => 'nullable|numeric|min:0'
        ], [
            'nombre_soport.string' => 'El nombre del soporte debe ser texto.',
            'nombre_soport.unique' => 'El nombre de soporte ya existe.',
            'nombre_soport.max' => 'El nombre del soporte no debe exceder los :max caracteres.',
            'disponibilidad.boolean' => 'La disponibilidad debe ser válida.',
            'precio.numeric' => 'El precio debe ser un valor numérico.',
            'precio.min' => 'El precio no puede ser negativo.'
        ]);


        // Actualizar solo los campos que tienen datos
        if ($peticion->filled('nombre_soport')) {
            $soporte->nombre_soport = $datosValidados['nombre_soport'];
        }
        if ($peticion->filled('disponibilidad')) {
            $soporte->disponibilidad = $datosValidados['disponibilidad'];
        }
        if ($peticion->filled('precio')) {
            $soporte->precio = $datosValidados['precio'];
        }

        // Guardar y devolver resultado
        $guardado = $soporte->save();
        if ($guardado) {
            return view('errores.exito', ['mensaje' => "Soporte con ID {$id} correctamente modificado"]);
        } else {
            return view('errores.error', ['mensaje' => "No se ha podido modificar el soporte con ID {$id}"]);
        }
    }


    /**
     * Filtra los soportes según los parámetros de la petición y devuelve
     * una lista paginada.
     *
     * @param \Illuminate\Http\Request $peticion
     * @return \Illuminate\View\View
     */
    public function filtrarSoportes(Request $peticion)
    {
        // Aplicamos los filtros y devolvemos la lista (aceptando GET y POST)
        $soportes = Soporte::query()
            ->when($peticion->filled('identificacion'), function ($consulta) use ($peticion) {
                return $consulta->where('id', $peticion->identificacion);
            })
            ->when($peticion->filled('nombre_soport'), function ($consulta) use ($peticion) {
                return $consulta->where('nombre_soport', 'like', '%' . $peticion->nombre_soport . '%');
            })
            ->when($peticion->filled('disponibilidad'), function ($consulta) use ($peticion) {
                return $consulta->where('disponibilidad', $peticion->disponibilidad);
            })
            ->when($peticion->filled('precio_minimo'), function ($consulta) use ($peticion) {
                return $consulta->where('precio', '>=', $peticion->precio_minimo);
            })
            ->when($peticion->filled('precio_maximo'), function ($consulta) use ($peticion) {
                return $consulta->where('precio', '<=', $peticion->precio_maximo);
            })
            ->paginate(15)
            ->appends($peticion->except('page'));

        $mensaje = "Encontrados " . $soportes->total() . " soportes en la base de datos según los filtros aplicados";
        return view('parciales.listados.listarsoportes', compact('soportes', 'mensaje'));
    }

    /**
     * Muestra el formulario para crear un nuevo soporte.
     *
     * @return \Illuminate\View\View
     */
    public function mostrarFormNuevoSoporte()
    {
        return view('administracion.formularios.formnuevosoporte');
    }

    /**
     * Registra un nuevo soporte en la base de datos.
     *
     * Valida los datos del form y crea el registro.
     *
     * @param \Illuminate\Http\Request $peticion
     * @return \Illuminate\View\View
     */
    public function registrarNuevoSoporte(Request $peticion)
    {
        // 1. Validar los datos del formulario
        $datosValidados = $peticion->validate([
            'nombre_soport' => 'required|string|max:50|unique:soportes,nombre_soport',
            'disponibilidad' => 'required|boolean',
            'precio' => 'required|numeric|min:0'
        ], [
            'nombre_soport.required' => 'El nombre del soporte es obligatorio.',
            'nombre_soport.string' => 'El nombre del soporte debe ser texto.',
            'nombre_soport.max' => 'El nombre del soporte no debe exceder los :max caracteres.',
            'nombre_soport.unique' => 'El nombre de soporte ya existe.',
            'disponibilidad.required' => 'La disponibilidad es obligatoria.',
            'disponibilidad.boolean' => 'La disponibilidad debe ser válida.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un valor numérico.',
            'precio.min' => 'El precio no puede ser negativo.'
        ]);

        // 2. Crear soporte usando el método create
        $soporte = Soporte::create([
            'nombre_soport' => $datosValidados['nombre_soport'],
            'disponibilidad' => $datosValidados['disponibilidad'],
            'precio' => $datosValidados['precio']
        ]);

        if ($soporte) {
            return view('errores.exito', ['mensaje' => "Soporte '{$soporte->nombre_soport}' registrado con éxito."]);
        } else {
            return view('errores.error', ['mensaje' => 'Error en el registro del soporte.']);
        }
    }
}
