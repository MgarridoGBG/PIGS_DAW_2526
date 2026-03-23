<?php

namespace App\Http\Controllers;

use App\Models\Formato;

use Illuminate\Http\Request;

/**
 * Controlador para gestionar formatos de fotos.
 *
 * Listar, filtrar, crear, editar y borrar formatos.
 *
 */
class FormatoController extends Controller
{
    /**
     * Lista los formatos existentes paginados.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function listarFormatos()
    {
        $formatos = Formato::paginate(15); // Eager loading de la relación 'role'cargando los roles asociados a los usuarios de antemano
        $mensaje = "Encontrados " . $formatos->total() . " formatos en la base de datos";
        return view('parciales.listados.listaformatos', compact('formatos', 'mensaje'));
    }

    /**
     * Filtra formatos según parámetros y devuelve una lista paginada.
     *
     * Soporta GET y POST por necesidad en la paginación.
     *
     * @param  \Illuminate\Http\Request  $peticion
     * @return \Illuminate\Contracts\View\View
     */
    public function filtrarFormatos(Request $peticion)
    {
        // Aplicamos los filtros y devolvemos la lista.
        $formatos = Formato::query()
            ->when($peticion->filled('identificacion'), function ($consulta) use ($peticion) {
                return $consulta->where('id', $peticion->identificacion);
            })
            ->when($peticion->filled('ancho'), function ($consulta) use ($peticion) {
                return $consulta->where('ancho', $peticion->ancho);
            })
            ->when($peticion->filled('alto'), function ($consulta) use ($peticion) {
                return $consulta->where('alto', $peticion->alto);
            })
            ->when($peticion->filled('nombre'), function ($consulta) use ($peticion) {
                return $consulta->where('nombre_format', 'like', '%' . $peticion->nombre . '%');
            })
            ->paginate(15)
            ->appends($peticion->except('page'));

        $mensaje = "Encontrados " . $formatos->total() . " formatos en la base de datos según los filtros aplicados";
        return view('parciales.listados.listaformatos', compact('formatos', 'mensaje'));
    }

    /**
     * Elimina un formato identificado por ID.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function borrarFormato($id)
    {
        $formato = Formato::find($id);

        if ($formato) {
            $eliminado = $formato->delete();
            if ($eliminado) {
                return view('errores.exito', ['mensaje' => "Formato {$formato->nombre_format} eliminado con exito"]);
            }
        }

        return view('errores.error', ['mensaje' => "No se ha podido eliminar el formato {$id}"]);
    }

    /**
     * Muestra el formulario de edición para un formato.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function mostrarFormEditarFormato($id)
    {
        $formato = Formato::findOrFail($id);

        return view('administracion.formularios.formeditarformato', [
            'formato' => $formato
        ]);
    }

    /**
     * Procesa el formulario de edición de un formato.
     *
     * Valida los campos y actualiza el modelo solo con los valores presentes en el form.
     *
     * @param  \Illuminate\Http\Request  $peticion
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function procesarFormEditarFormato(Request $peticion, $id)
    {
        // Si la petición es GET mostramos error
        if ($peticion->isMethod('get')) {
            return view('errores.error', ['mensaje' => 'Acceso denegado Vuelva a la página anterior y utiliza el formulario de búsqueda.']);
        }

        $formato = Formato::findOrFail($id);

        // Validar los datos recibidos
        $datosValidados = $peticion->validate([
            'nombre_format' => 'nullable|string|max:50|unique:formatos,nombre_format',
            'ancho' => 'nullable|numeric|min:0',
            'alto' => 'nullable|numeric|min:0'
        ], [
            'nombre_format.string' => 'El nombre del formato debe ser texto.',
            'nombre_format.max' => 'El nombre del formato no debe exceder los :max caracteres.',
            'nombre_format.unique' => 'El nombre del formato ya existe.',
            'ancho.numeric' => 'El ancho debe ser un valor numérico.',
            'ancho.min' => 'El ancho no puede ser negativo.',
            'alto.numeric' => 'El alto debe ser un valor numérico.',
            'alto.min' => 'El alto no puede ser negativo.'
        ]);

        // Actualizar solo los campos que tienen datos
        if ($peticion->filled('nombre_format')) {
            $formato->nombre_format = $datosValidados['nombre_format'];
        }
        if ($peticion->filled('ancho')) {
            $formato->ancho = $datosValidados['ancho'];
        }
        if ($peticion->filled('alto')) {
            $formato->alto = $datosValidados['alto'];
        }

        // Guardar los cambios
        $guardado = $formato->save();

        if ($guardado) {
            return view('errores.exito', ['mensaje' => "Formato con ID {$id} correctamente modificado"]);
        } else {
            return view('errores.error', ['mensaje' => "No se ha podido modificar el formato con ID {$id}"]);
        }
    }

    /**
     * Muestra el formulario para crear un nuevo formato.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function mostrarFormNuevoFormato()
    {
        return view('administracion.formularios.formnuevoformato');
    }
    /**
     * Valida y registra un nuevo formato en la base de datos.
     * o devuelve una vista de error.
     * 
     * @param  \Illuminate\Http\Request  $peticion
     * @return \Illuminate\Contracts\View\View
     */
    public function registrarNuevoFormato(Request $peticion)
    {
        //Validar los datos del formulario
        $datosValidados = $peticion->validate([
            'nombre_format' => 'required|string|max:50|unique:formatos,nombre_format',
            'ancho' => 'required|numeric|min:0',
            'alto' => 'required|numeric|min:0'
        ], [
            // Aunque muchos de estos restraints ya se aplican en en front, los valido también en el back.
            'nombre_format.required' => 'El nombre del formato es obligatorio.',
            'nombre_format.string' => 'El nombre del formato debe ser texto.',
            'nombre_format.unique' => 'El nombre del formato ya existe.',
            'nombre_format.max' => 'El nombre del formato no debe exceder los :max caracteres.',
            'ancho.required' => 'El ancho es obligatorio.',
            'ancho.numeric' => 'El ancho debe ser un valor numérico.',
            'ancho.min' => 'El ancho no puede ser negativo.',
            'alto.required' => 'El alto es obligatorio.',
            'alto.numeric' => 'El alto debe ser un valor numérico.',
            'alto.min' => 'El alto no puede ser negativo.'
        ]);

        //Crear formato.
        $formato = Formato::create([
            'nombre_format' => $datosValidados['nombre_format'],
            'ancho' => $datosValidados['ancho'],
            'alto' => $datosValidados['alto']
        ]);

        if ($formato) {
            return view('errores.exito', ['mensaje' => "Formato '{$formato->nombre_format}' registrado con éxito."]);
        } else {
            return view('errores.error', ['mensaje' => 'Error en el registro del formato.']);
        }
    }
}
