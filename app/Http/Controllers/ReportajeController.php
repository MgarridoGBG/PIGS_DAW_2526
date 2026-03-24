<?php

namespace App\Http\Controllers;

use App\Models\Reportaje;
use App\Models\Fotografia;
use App\Enums\TipoReportaje;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Enums\ExtensionesFotos;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * Controlador de Reportajes
 *
 * Gestiona la creación, edición, listado, borrado y búsquedas
 * de reportajes, así como la comprobación de fotos en el
 * almacenamiento privado y la detección de reportajes "fantasma".
 */
class ReportajeController extends Controller
{

    /**
     * Lista todos los reportajes paginados.
     *
     * @return \Illuminate\View\View
     */
    public function listarReportajes()
    {
        $reportajes = Reportaje::paginate(15);
        $mensaje = "Encontrados " . $reportajes->total() . " reportajes en la base de datos";
        return view('parciales.listados.listarreportajes', compact('reportajes', 'mensaje'));
    }

    /**
     * Muestra el formulario para editar un reportaje.
     *
     * Carga el reportaje y los tipos disponibles desde el enum 'TipoReportaje'.
     *
     * @param int $id ID del reportaje
     * @return \Illuminate\View\View
     */
    public function mostrarFormEditarReportaje($id)
    {
        $reportaje = Reportaje::findOrFail($id);
        $tipos = TipoReportaje::values();
        return view('administracion.formularios.formeditarreportaje', [
            'reportaje' => $reportaje,
            'tipos' => $tipos
        ]);
    }

    /**
     * Elimina un reportaje y, opcionalmente, su carpeta de fotos.
     *
     * - Comprueba la existencia de la carpeta en storage.
     * - Solicita confirmación si es necesario.
     *
     * @param int $id ID del reportaje a eliminar
     * @return \Illuminate\View\View 
     */
    public function borrarReportaje($id)
    {
        $reportaje = Reportaje::find($id);
        if (!$reportaje) {
            return view('errores.error', ['mensaje' => "No se ha encontrado el reportaje con ID:{$id}"]);
        }

        // Verificar si existe la carpeta en storage/app/private/fotosreportajes
        $rutaCarpeta = storage_path('app/private/fotosreportajes/' . $reportaje->codigo);
        $existeCarpeta = file_exists($rutaCarpeta);

        // Si la carpeta existe y no se ha indicado acción, mostrar confirmación
        if ($existeCarpeta && !request()->filled('accion_carpeta')) {
            return view('administracion.confirmaciones.confirmarborrarcarpeta', [
                'reportaje' => $reportaje,
                'codigo' => $reportaje->codigo
            ]);
        }

        // Si el usuario canceló, no eliminar nada
        if (request()->input('accion_carpeta') === 'cancelar') {
            return redirect()->route('listarreportajes')
                ->with('mensaje', 'Operación cancelada. No se ha eliminado el reportaje.');
        }

        // Eliminar carpeta si se ha indicado
        if ($existeCarpeta && request()->input('accion_carpeta') === 'eliminar_carpeta') {
            try {
                // Función recursiva para eliminar carpeta y su contenido
                function eliminarDirectorio($dir)
                {
                    if (!file_exists($dir)) {
                        return true;
                    }
                    if (!is_dir($dir)) {
                        return unlink($dir);
                    }
                    foreach (scandir($dir) as $item) {
                        // Saltar . y .. para evitar problemas de recursión infinita y errores de eliminación
                        if ($item == '.' || $item == '..') { // Saltar . y .. para evitar problemas de recursión infinita y errores de eliminación
                            continue;
                        }
                        if (!eliminarDirectorio($dir . DIRECTORY_SEPARATOR . $item)) {
                            return false;
                        }
                    }
                    return rmdir($dir);
                }

                eliminarDirectorio($rutaCarpeta);
            } catch (\Exception $e) {
                return view('errores.error', ['mensaje' => 'Error al eliminar la carpeta: ' . $e->getMessage()]);
            }
        }

        // Eliminar el reportaje
        try {
            $codigo = $reportaje->codigo;
            $eliminado = $reportaje->delete();

            // Mostrar mensaje de éxito con información adicional sobre la carpeta
            if ($eliminado) {
                $mensajeExtra = '';
                if ($existeCarpeta && request()->input('accion_carpeta') === 'eliminar_carpeta') {
                    $mensajeExtra = ' La carpeta y sus fotos han sido eliminadas.';
                } elseif ($existeCarpeta && request()->input('accion_carpeta') === 'no_eliminar') {
                    $mensajeExtra = ' La carpeta ha sido conservada.';
                }
                return view('errores.exito', ['mensaje' => "Reportaje {$codigo} eliminado con éxito." . $mensajeExtra]);
            }
        } catch (\Exception $excepcion) {
            return view('errores.error', ['mensaje' => "Error al eliminar el reportaje: " . $excepcion->getMessage()]);
        }

        return view('errores.error', ['mensaje' => "No se ha podido eliminar el reportaje con ID:{$id}"]);
    }

    /**
     * Procesa la edición de un reportaje.
     *
     * Valida la petición, gestiona el renombrado de la carpeta
     * asociada si cambia el código y actualiza el modelo.
     *
     * @param \Illuminate\Http\Request $peticion
     * @param int $id ID del reportaje
     * @return \Illuminate\View\View
     */
    public function procesarFormEditarReportaje(Request $peticion, $id)
    {
        // Si la petición es GET mostramos la vista de error solicitada
        if ($peticion->isMethod('get')) {
            return view('errores.error', ['mensaje' => 'Acceso denegado. Vuelva a la página anterior y utiliza el formulario de búsqueda.']);
        }

        $reportaje = Reportaje::findOrFail($id);

        // Validar los datos recibidos
        $datosValidados = $peticion->validate([
            'email_usuario' => 'nullable|email|exists:users,email',
            'tipo' => ['nullable', 'string', 'max:50', Rule::in(TipoReportaje::values())],
            'codigo' => 'nullable|string|max:20',
            'descripcion' => 'nullable|string|max:250',
            'fecha_report' => 'nullable|date',
            'publico' => 'nullable|boolean',
            'accion_carpeta' => 'nullable|in:renombrar,no_renombrar,cancelar'
        ], [
            'tipo.string' => 'El tipo debe ser texto.',
            'tipo.max' => 'El tipo no debe exceder los :max caracteres.',
            'codigo.string' => 'El código debe ser texto.',
            'codigo.max' => 'El código no debe exceder los :max caracteres.',
            'descripcion.string' => 'La descripción debe ser texto.',
            'descripcion.max' => 'La descripción no debe exceder los :max caracteres.',
            'fecha_report.date' => 'La fecha debe ser una fecha válida.',
            'email_usuario.exists' => 'El usuario seleccionado no existe.',
            'publico.boolean' => 'El campo público debe ser válido.'
        ]);

        // Verificar si se está cambiando el código
        $codigoAntiguo = $reportaje->codigo;
        $codigoNuevo = $peticion->filled('codigo') ? $datosValidados['codigo'] : $codigoAntiguo;
        $cambiaCodigo = ($codigoAntiguo !== $codigoNuevo);

        if ($cambiaCodigo) {
            // Verificar si ya existe una carpeta con el nuevo código
            $rutaCarpetaNueva = storage_path('app/private/fotosreportajes/' . $codigoNuevo);
            if (file_exists($rutaCarpetaNueva)) {
                return view('errores.error', ['mensaje' => 'Ya existe el código de reportaje']);
            }

            // Si no se ha indicado acción, mostrar confirmación
            if (!$peticion->filled('accion_carpeta')) {
                return view('administracion.confirmaciones.confirmarrenomcarpeta', [
                    'reportaje' => $reportaje,
                    'datosReportaje' => $datosValidados,
                    'codigoAntiguo' => $codigoAntiguo,
                    'codigoNuevo' => $codigoNuevo
                ]);
            }

            // Si el usuario canceló, no modificar nada
            if ($peticion->input('accion_carpeta') === 'cancelar') {
                return redirect()->route('formeditarreportaje', ['id' => $id])
                    ->with('mensaje', 'Operación cancelada. No se ha modificado el reportaje.');
            }

            // Renombrar carpeta si es necesario
            if ($peticion->input('accion_carpeta') === 'renombrar') {
                $rutaCarpetaAntigua = storage_path('app/private/fotosreportajes/' . $codigoAntiguo);
                if (file_exists($rutaCarpetaAntigua)) {
                    try {
                        rename($rutaCarpetaAntigua, $rutaCarpetaNueva);
                    } catch (\Exception $e) {
                        return view('errores.error', ['mensaje' => 'Error al renombrar la carpeta: ' . $e->getMessage()]);
                    }
                }
            }
        }

        try {
            // Actualizar solo los campos que tienen datos
            if ($peticion->filled('tipo')) {
                $reportaje->tipo = $datosValidados['tipo'];
            }
            if ($peticion->filled('codigo')) {
                $reportaje->codigo = $datosValidados['codigo'];
            }
            if ($peticion->filled('descripcion')) {
                $reportaje->descripcion = $datosValidados['descripcion'];
            }

            if ($peticion->filled('email_usuario')) {
                $usuario = User::where('email', $datosValidados['email_usuario'])->first();
                if ($usuario) {
                    $reportaje->user_id = $usuario->id;
                }
            }

            if ($peticion->filled('fecha_report')) {
                $reportaje->fecha_report = $datosValidados['fecha_report'];
            }
            if ($peticion->has('publico')) {
                $reportaje->publico = $datosValidados['publico'];
            }

            // Guardar los cambios
            $guardado = $reportaje->save();

            if ($guardado) {
                $mensajeExtra = '';
                if ($cambiaCodigo && $peticion->input('accion_carpeta') === 'renombrar') {
                    $mensajeExtra = ' La carpeta ha sido renombrada.';
                } elseif ($cambiaCodigo && $peticion->input('accion_carpeta') === 'no_renombrar') {
                    $mensajeExtra = ' La carpeta no ha sido renombrada.';
                }
                return view('errores.exito', ['mensaje' => "Reportaje con ID {$id} correctamente modificado." . $mensajeExtra]);
            } else {
                return view('errores.error', ['mensaje' => 'Error en la modificación de datos']);
            }
        } catch (\Exception $excepcion) {
            return view('errores.error', ['mensaje' => "No se ha podido modificar el reportaje con ID {$id}"]);
        }
    }

    /**
     * Filtra reportajes según criterios de la petición y devuelve
     * una lista paginada.
     *
     * @param \Illuminate\Http\Request $peticion
     * @return \Illuminate\View\View
     */
    public function filtrarReportajes(Request $peticion)
    {
        // Aplicamos los filtros y devolvemos la lista (aceptando GET y POST)
        $reportajes = Reportaje::query()
            ->when($peticion->filled('identificacion'), function ($consulta) use ($peticion) {
                return $consulta->where('id', $peticion->identificacion);
            })
            ->when($peticion->filled('user_id'), function ($consulta) use ($peticion) {
                return $consulta->where('user_id', $peticion->user_id);
            })
            ->when($peticion->filled('email_usuario'), function ($consulta) use ($peticion) {
                return $consulta->whereHas('user', function ($subConsulta) use ($peticion) {
                    $subConsulta->where('email', 'like', '%' . $peticion->email_usuario . '%');
                });
            })
            ->when($peticion->filled('tipo'), function ($consulta) use ($peticion) {
                return $consulta->where('tipo', $peticion->tipo);
            })
            ->when($peticion->filled('codigo'), function ($consulta) use ($peticion) {
                return $consulta->where('codigo', 'like', '%' . $peticion->codigo . '%');
            })
            ->when($peticion->filled('descripcion'), function ($consulta) use ($peticion) {
                return $consulta->where('descripcion', 'like', '%' . $peticion->descripcion . '%');
            })
            ->when($peticion->filled('fecha_report'), function ($consulta) use ($peticion) {
                return $consulta->where('fecha_report', $peticion->fecha_report);
            })
            ->when($peticion->filled('publico'), function ($consulta) use ($peticion) {
                return $consulta->where('publico', $peticion->publico);
            })
            ->paginate(15)
            ->appends($peticion->except('page'));

        $mensaje = "Encontrados " . $reportajes->total() . " reportajes en la base de datos según los filtros aplicados";
        return view('parciales.listados.listarreportajes', compact('reportajes', 'mensaje'));
    }

    /**
     * Muestra el formulario para registrar un nuevo reportaje.
     *
     * @return \Illuminate\View\View
     */
    public function mostrarFormNuevoReportaje()
    {
        $tipos = TipoReportaje::values();
        return view('administracion.formularios.formnuevoreportaje', compact('tipos'));
    }

    /**
     * Registra un nuevo reportaje, crea la carpeta en storage si se solicita
     * y añade fotografías encontradas en la carpeta al reportaje.
     *
     * @param \Illuminate\Http\Request $peticion
     * @return \Illuminate\View\View
     */
    public function registrarNuevoReportaje(Request $peticion)
    {
        // 1. Validar los datos del formulario
        $datosValidados = $peticion->validate([
            'tipo' => ['required', 'string', 'max:50', Rule::in(TipoReportaje::values())],
            'codigo' => 'required|string|unique:reportajes,codigo|max:20',
            'descripcion' => 'nullable|string|max:250',
            'fecha_report' => 'required|date',
            'email_usuario' => 'required|exists:users,email',
            'publico' => 'nullable|boolean',
            'accion_carpeta' => 'nullable|in:crear,no_crear,cancelar',
            'accion_fotos' => 'nullable|in:si,no,cancelar'
        ], [
            'tipo.required' => 'El tipo de reportaje es obligatorio.',
            'tipo.string' => 'El tipo debe ser texto.',
            'tipo.max' => 'El tipo no debe exceder los :max caracteres.',
            'codigo.required' => 'El código es obligatorio.',
            'codigo.unique' => 'Ya existe un reportaje con este código.',
            'codigo.string' => 'El código debe ser texto.',
            'codigo.max' => 'El código no debe exceder los :max caracteres.',
            'descripcion.string' => 'La descripción debe ser texto.',
            'descripcion.max' => 'La descripción no debe exceder los :max caracteres.',
            'fecha_report.required' => 'La fecha del reportaje es obligatoria.',
            'fecha_report.date' => 'La fecha debe ser una fecha válida.',
            'email_usuario.required' => 'El usuario es obligatorio.',
            'email_usuario.exists' => 'El usuario seleccionado no existe.',
            'publico.boolean' => 'El campo público debe ser válido.'
        ]);

        // 2. Verificar si existe la carpeta en storage/app/private/fotosreportajes
        $rutaCarpeta = storage_path('app/private/fotosreportajes/' . $datosValidados['codigo']);
        $existeCarpeta = file_exists($rutaCarpeta);

        // Si la carpeta existe, verificar si hay fotos dentro
        if ($existeCarpeta) {
            $fotosEncontradas = $this->verificarFotos($rutaCarpeta);

            // Si hay fotos y no se ha indicado acción, preguntar al usuario
            if (!empty($fotosEncontradas) && !$peticion->filled('accion_fotos')) {
                return view('administracion.confirmaciones.confirmaranadirfotos', [
                    'datosReportaje' => $datosValidados,
                    'codigo' => $datosValidados['codigo'],
                    'fotos' => $fotosEncontradas,
                    'cantidadFotos' => count($fotosEncontradas)
                ]);
            }

            // Si el usuario canceló, no crear nada
            if ($peticion->input('accion_fotos') === 'cancelar') {
                return redirect()->route('formnuevoreportaje')
                    ->with('mensaje', 'Operación cancelada. No se ha creado el reportaje.');
            }
        }

        // Si la carpeta no existe y no se ha indicado acción, mostrar confirmación
        if (!$existeCarpeta && !$peticion->filled('accion_carpeta')) {
            return view('administracion.confirmaciones.confirmarcrearcarpeta', [
                'datosReportaje' => $datosValidados,
                'codigo' => $datosValidados['codigo']
            ]);
        }

        // Si el usuario canceló, no crear nada
        if ($peticion->input('accion_carpeta') === 'cancelar') {
            return redirect()->route('formnuevoreportaje')
                ->with('mensaje', 'Operación cancelada. No se ha creado el reportaje.');
        }

        // 3. Crear carpeta si es necesario
        if (!$existeCarpeta && $peticion->input('accion_carpeta') === 'crear') {
            try {
                mkdir($rutaCarpeta, 0755, true);
            } catch (\Exception $e) {
                return view('errores.error', ['mensaje' => 'Error al crear la carpeta: ' . $e->getMessage()]);
            }
        }

        // 4. Crear reportaje en la BD
        $usuario = User::where('email', $datosValidados['email_usuario'])->first();
        // Aunque la validación ya asegura que el usuario existe, es buena práctica verificarlo antes de usarlo
        if (!$usuario) {
            return view('errores.error', ['mensaje' => 'No se ha encontrado el usuario con ese email.']);
        }

        $añadirFotos = $existeCarpeta && $peticion->input('accion_fotos') === 'si';

        try {
            // Usamos una transacción para asegurar que la creación del reportaje y la asociación de fotos se realicen de forma correcta.
            $reportaje = DB::transaction(function () use ($datosValidados, $usuario, $añadirFotos, $rutaCarpeta) {
                $reportaje = Reportaje::create([
                    'tipo'=> $datosValidados['tipo'],
                    'codigo'=> $datosValidados['codigo'],
                    'descripcion'=> $datosValidados['descripcion'] ?? null,
                    'fecha_report'=> $datosValidados['fecha_report'],
                    'user_id'=> $usuario->id,
                    'publico'=> $datosValidados['publico'] ?? 0
                ]);

                if ($añadirFotos) {
                    $fotosEncontradas = $this->verificarFotos($rutaCarpeta);
                    // Asociar cada foto encontrada al reportaje recién creado
                    foreach ($fotosEncontradas as $nombreFoto) {
                        Fotografia::create([
                            'nombre_foto' => $nombreFoto,
                            'reportaje_id' => $reportaje->id
                        ]);
                    }
                }

                return $reportaje;
            });

            $mensajeExtra = '';
            
            if ($añadirFotos) {
                $totalFotos = count($this->verificarFotos($rutaCarpeta));
                $mensajeExtra .= " Se han añadido {$totalFotos} fotografías al reportaje.";
            } elseif ($existeCarpeta && $peticion->input('accion_fotos') === 'no') {
                $mensajeExtra .= ' No se han añadido las fotografías existentes en la carpeta.';
            }

            if (!$existeCarpeta && $peticion->input('accion_carpeta') === 'crear') {
                $mensajeExtra .= ' La carpeta ha sido creada.';
            } elseif (!$existeCarpeta && $peticion->input('accion_carpeta') === 'no_crear') {
                $mensajeExtra .= ' La carpeta no ha sido creada.';
            }

            return view('errores.exito', ['mensaje' => "Reportaje '{$reportaje->codigo}' registrado con éxito." . $mensajeExtra]);

        } catch (\Exception $excepcion) {
            $mensajeExtra = ' Comprueba los nombres y formatos de las fotografías en la carpeta.';
            return view('errores.error', ['mensaje' => 'No se ha podido crear el reportaje, algunas de las fotos no se han podido añadir.'. $mensajeExtra]);
        }
    }

    /**
     * Verifica si existen archivos de imagen en una carpeta y devuelve sus nombres base
     * para su posible asociación a un reportaje. Solo se consideran archivos con extensiones
     * registradas en el enum ExtensionesFotos.
     * 
     * @param string $rutaCarpeta Ruta de la carpeta a verificar
     * @return array Array con los nombres base de los archivos de imagen encontrados
     */
    public function verificarFotos($rutaCarpeta)
    {
        $nombresImagenes = [];

        // Verificar si la carpeta existe
        if (!file_exists($rutaCarpeta) || !is_dir($rutaCarpeta)) {
            return $nombresImagenes;
        }

        // Extensiones de imagen válidas
        $extensionesValidas = ExtensionesFotos::values();

        // Leer el contenido de la carpeta
        $archivos = scandir($rutaCarpeta);

        foreach ($archivos as $archivo) {
            // Saltar . y ..
            if ($archivo === '.' || $archivo === '..') {
                continue;
            }

            $rutaCompleta = $rutaCarpeta . DIRECTORY_SEPARATOR . $archivo;

            // Verificar que sea un archivo (no una carpeta)
            if (is_file($rutaCompleta)) {
                // Obtener la extensión del archivo
                $extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));

                // Verificar si es una imagen
                if (in_array($extension, $extensionesValidas)) {
                    // Añadir el nombre del archivo con extensión
                    $nombresImagenes[] = $archivo;
                }
            }
        }

        return $nombresImagenes;
    }

    /**
     * Busca reportajes que no tienen carpeta asociada en storage
     * (reportajes "fantasma") y devuelve una lista paginada.     *
     * En petición normal devuelve la vista con spinner.
     * En petición AJAX realiza el procesamiento lento y devuelve la vista parcial con los datos.
     *
     * @return \Illuminate\View\View
     */
    public function buscarReportajesFantasma(Request $request)
    {
        // Petición es normal (no es Ajax): mostrar la página con el spinner; el contenido se carga vía AJAX
        if (!$request->ajax()) {
            return view('parciales.listados.listarreporfantasma');
        }
        //Si la peticion no es normal (es petición AJAX), devolver la vista parcial con los datos
        $rutaBase = storage_path('app/private/fotosreportajes');

        // Obtener todos los reportajes de la BD
        $todosLosReportajes = Reportaje::all();

        // Filtrar los que NO tienen carpeta asociada
        $fantasmas = $todosLosReportajes->filter(function ($reportaje) use ($rutaBase) {
            $rutaCarpeta = $rutaBase . DIRECTORY_SEPARATOR . $reportaje->codigo;
            return !file_exists($rutaCarpeta) || !is_dir($rutaCarpeta);
        });

        $total = $fantasmas->count();

        // Paginación manual sobre la colección, he tenido que hacerla manual porque
        //paginate() no existe en Collection, solo en Builder, y aquí ya tenemos la colección filtrada
        $paginaActual = LengthAwarePaginator::resolveCurrentPage();
        $porPagina = 15;
        $itemsPagina = $fantasmas->values()->slice(($paginaActual - 1) * $porPagina, $porPagina);
        $reportajes = new LengthAwarePaginator(
            $itemsPagina,
            $total,
            $porPagina,
            $paginaActual,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        // Mostrar mensaje con el número de reportajes fantasma encontrados y devolver la vista parcial
        $mensaje = $total > 0 ? "Reportajes fantasma encontrados: {$total}" : "No se han encontrado reportajes fantasma.";
        return view('parciales.listados.tablareporfantasma', compact('reportajes', 'mensaje'));
    }
}
