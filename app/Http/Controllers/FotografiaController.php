<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reportaje;
use App\Models\Fotografia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

/**
     * Controlador para la gestión de fotografías y galerías de reportajes.
     *
     * Incluye funciones para mostrar galerías, diferenciando entre públicas y privadas,
     * métodos paraservir archivos desde storage, listar/filtrar/registrar/editar
     * y eliminar fotografías.
     */

class FotografiaController extends Controller
{    
    /**
     * Muestra las fotografías de un reportaje/coleccción.
     *
     * Verifica permisos y devuelve la vista correspondiente.
     *
     * @param int $id ID del reportaje
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response
     */
    public function mostrarFotosReportaje($id)
    {
        // Carga el reportaje por su id y sus relación con el usuario y su role y verifica si existe
        $reportaje = Reportaje::with('user.role')->find($id);
        if (!$reportaje) {
            return view('errores.error', [
                'mensaje' => 'Reportaje no encontrado'
            ]);
        }

        // Verificar que el reportaje pertenece al usuario autenticado, es público, o el usuario es admin/empleado
        $userRole = Auth::check() && Auth::user()->role ? Auth::user()->role->nombre_role : null;
        $userEsAdminOEmpleado = in_array($userRole, ['admin', 'empleado'], true);
        if (!($reportaje->user_id == Auth::id() || ($reportaje->publico ?? false) || $userEsAdminOEmpleado)) {
            return view('errores.error', [
                'mensaje' => 'No tiene permiso para ver este reportaje'
            ]);
        }

        // Paginar la relación de fotos desde el modelo.
        $fotografiasPaginadas = $reportaje->fotografias()->paginate(9);

        // Determinar la vista: si el reportaje es público, usar la vista pública
        $vista = ($reportaje->publico ?? false) ? 'zonapublica.galeriareportajepublico' : 'zonaprivada.galeriareportajeprivado';

        return view($vista, [
            'reportaje' => $reportaje,
            'fotografias' => $fotografiasPaginadas,
        ]);
    }

    /**
     * Muestra la galería pública con fotografías de reportajes marcados como públicos.
     * y lista los reportajes públicos (colecciones).
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function mostrarFotosPublicas()
    {
        // Obtener todas las fotos que pertenecen a reportajes marcados como públicos y los reportajes publicos.
        $fotografiasPublicasPaginadas = Fotografia::whereHas('reportaje', function ($query) {
            $query->where('publico', true);
        })->paginate(9);
        $colecciones = Reportaje::where('publico', true)->get();
        $nombresEtiquetas = \App\Models\Etiqueta::pluck('nombre_etiqueta')->toArray();


        // Pasar las fotos y colecciones a la vista
        return view('zonapublica.galeriapublica', [
            'fotografias' => $fotografiasPublicasPaginadas,
            'colecciones' => $colecciones,
            'nombresEtiquetas' => $nombresEtiquetas,
        ]);
    }

    /**
     * Muestra una foto privada si el usuario tiene permisos.
     *
     * @param int $id ID de la fotografía
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function mostrarFoto($id)
    {
        $foto = Fotografia::with('reportaje')->find($id);

        if (!$foto) {
            return view('errores.error', [
                'mensaje' => 'Foto no encontrada'
            ]);
        }

        // Permitir acceso si: el usuario autenticado es el propietario,
        // si el usuario tiene role 'admin' o 'empleado', o si el reportaje es público
        $idPropietario = $foto->reportaje->user_id ?? null;
        $publico = $foto->reportaje->publico ?? false;
        $userRole = Auth::check() && Auth::user()->role ? Auth::user()->role->nombre_role : null;
        $userEsAdminOEmpleado = in_array($userRole, ['admin', 'empleado'], true);
        $userEsPropietario = Auth::id() == $idPropietario;

        if (!($userEsPropietario || $userEsAdminOEmpleado || $publico)) {
            return view('errores.error', [
                'mensaje' => 'No autorizado'
            ]);
        }

        return view('zonaprivada.mostrarfoto', compact('foto'));
    }

    /**
     * Muestra una foto en la zona pública (solo si el reportaje es público).
     *
     * @param int $id ID de la fotografía
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function mostrarFotoPublica($id)
    {
        $foto = Fotografia::with('reportaje.user.role')->find($id);

        if (!$foto) {
            return view('errores.error', [
                'mensaje' => 'Foto no encontrada'
            ]);
        }

        // Verificar si la foto pertenece a un reportaje público
        if (!($foto->reportaje && ($foto->reportaje->publico ?? false))) {
            return view('errores.error', [
                'mensaje' => 'No autorizado'
            ]);
        }

        return view('zonapublica.mostrarfotopublica', compact('foto'));
    }

    /**
     * Sirve un archivo de imagen desde storage.
     *
     * La ruta corta debe ser "{codigoReportaje}/{nombreArchivo}". Se verifica
     * existencia en disco, asociación en BD y permisos.
     *
     * @param string $rutaCorta Ruta relativa corta dentro de `fotosreportajes`
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function servirFotoStorage(string $rutaCorta)
    {

        $rutaLarga = "fotosreportajes/$rutaCorta";

        if (!Storage::disk('local')->exists($rutaLarga)) {
            return view('errores.error', [
                'mensaje' => 'Foto no encontrada'
            ]);
        }

        // Dividir la ruta corta en partes separadas por '/'. Ej: [codigoRepor, filename]
        $partesRuta = explode('/', $rutaCorta);
        // Obtener el nombre de la foto (última parte del array resultante)
        $nombreFoto = end($partesRuta);
        // Obtener el código del reportaje (primera parte) o null si no existe
        $codigoRepor = $partesRuta[0] ?? null;

        // Buscar la Fotografia en la base de datos cargando su reportaje y el role del usuario
        $foto = Fotografia::with('reportaje.user.role')
            // Filtrar por el nombre del archivo
            ->where('nombre_foto', $nombreFoto)
            // Asegurar que el reportaje asociado tiene el código esperado (si se proporcionó)
            ->whereHas('reportaje', function ($q) use ($codigoRepor) {
                if ($codigoRepor) $q->where('codigo', $codigoRepor);
            })->first();

        // Si no existe la fotografía en la base de datos, devolver 404
        if (!$foto) {
            return view('errores.error', [
                'mensaje' => 'Foto no encontrada en la base de datos'
            ]);
        }

        // Obtener el id del propietario del reportaje, si el repor es publico y si el user es el propietario
        $idPropietario = $foto->reportaje->user_id ?? null;
        $publicoPrivado = $foto->reportaje->publico ?? false;
        $userEsPropietario = Auth::id() == $idPropietario;

        // Determinar role del usuario
        $userRole = Auth::check() && Auth::user()->role ? Auth::user()->role->nombre_role : null;
        $userEsAdminOEmpleado = in_array($userRole, ['admin', 'empleado'], true);

        // Permitir acceso solo si: propietario, o usuario es admin/empleado, o el reportaje es público
        if (!($userEsPropietario || $userEsAdminOEmpleado || $publicoPrivado)) {
            // Si no cumple las condiciones, devolver error.
            return view('errores.error', [
                'mensaje' => 'No autorizado para ver esta foto'
            ]);
        }

        // Si se cumplen las condiciones, servir el archivo con su tipo MIME.
        return response()->file(
            Storage::disk('local')->path($rutaLarga),
            ['Content-Type' => Storage::mimeType($rutaLarga)]
        );
    }

    /**
     * Lista todas las fotografías con paginación (administración).
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function listarFotografias()
    {
        $fotografias = Fotografia::with('reportaje.user')->paginate(15);
        $mensaje = "Encontradas " . $fotografias->total() . " fotografías en la base de datos";
        return view('parciales.listados.listarfotografias', compact('fotografias', 'mensaje'));
    }

    /**
     * Filtra fotografías según distintos parámetros recibidos en la petición.
     *    
     * @param \Illuminate\Http\Request $peticion
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function filtrarFotografias(Request $peticion)
    {
        // Aplicamos los filtros y devolvemos la lista (con GET o POST)
        $fotografias = Fotografia::query()
            ->with(['reportaje.user']) // Cargar relaciones necesarias
            ->when($peticion->filled('foto_id'), function ($consulta) use ($peticion) {
                return $consulta->where('id', $peticion->foto_id);
            })
            ->when($peticion->filled('nombre_foto'), function ($consulta) use ($peticion) {
                return $consulta->where('nombre_foto', 'like', '%' . $peticion->nombre_foto . '%');
            })
            ->when($peticion->filled('reportaje_id'), function ($consulta) use ($peticion) {
                return $consulta->where('reportaje_id', $peticion->reportaje_id);
            })
            ->when($peticion->filled('user_id'), function ($consulta) use ($peticion) {
                return $consulta->whereHas('reportaje', function ($q) use ($peticion) {
                    $q->where('user_id', $peticion->user_id);
                });
            })
            ->when($peticion->filled('reportaje_codigo'), function ($consulta) use ($peticion) {
                return $consulta->whereHas('reportaje', function ($q) use ($peticion) {
                    $q->where('codigo', 'like', '%' . $peticion->reportaje_codigo . '%');
                });
            })
            ->when($peticion->filled('propietario_email'), function ($consulta) use ($peticion) {
                return $consulta->whereHas('reportaje.user', function ($q) use ($peticion) {
                    $q->where('email', 'like', '%' . $peticion->propietario_email . '%');
                });
            })
            ->paginate(15)
            ->appends($peticion->except('page')); // Preservar parámetros de búsqueda en la paginación
        $mensaje = "Encontradas " . $fotografias->total() . " fotografías en la base de datos según los filtros aplicados";
        return view('parciales.listados.listarfotografias', compact('fotografias', 'mensaje'));
    }

    /**
     * Muestra el formulario para registrar una nueva fotografía.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function mostrarFormNuevaFotografia()
    {
        return view('administracion.formularios.formnuevafotografia');
    }

    /**
     * Registra una nueva fotografía en la base de datos.
     *
     * Valida entrada, comprueba existencia del archivo en storage y ofrece
     * confirmación si el archivo físico no existe. Puede recibir una acción
     * 'accion_archivo' para confirmar o cancelar.
     *
     * @param \Illuminate\Http\Request $peticion
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function registrarNuevaFotografia(Request $peticion)
    {
        // Validar los datos del formulario
        $datosValidados = $peticion->validate([
            'nombre_foto' => 'required|string|max:100',
            'reportaje_codigo' => 'required|string|max:20|exists:reportajes,codigo',
            'accion_archivo' => 'nullable|in:agregar,cancelar'

        ], [
            'nombre_foto.required' => 'El nombre de la fotografía es obligatorio.',
            'nombre_foto.string' => 'El nombre debe ser texto.',
            'nombre_foto.max' => 'El nombre no debe exceder los :max caracteres.',
            'reportaje_codigo.required' => 'El código del reportaje es obligatorio.',
            'reportaje_codigo.string' => 'El código debe ser texto.',
            'reportaje_codigo.exists' => 'El código del reportaje no existe.',
            'reportaje_codigo.max' => 'El código no debe exceder los :max caracteres.',
        ]);

        try {
            // Buscar el reportaje por su código
            $reportaje = Reportaje::where('codigo', $datosValidados['reportaje_codigo'])->first();

            if (!$reportaje) {
                return view('errores.error', ['mensaje' => 'No se encontró un reportaje con el código: ' . $datosValidados['reportaje_codigo']]);
            }

            // Verificar si ya existe una fotografía con ese nombre en el reportaje
            $fotoExistente = Fotografia::where('reportaje_id', $reportaje->id)
                ->where('nombre_foto', $datosValidados['nombre_foto'])
                ->first();

            if ($fotoExistente) {
                return view('errores.error', ['mensaje' => 'Ya existe una fotografía con ese nombre en el reportaje ' . $reportaje->codigo]);
            }

            // Verificar si existe el archivo físico en storage
            $rutaArchivo = storage_path('app/private/fotosreportajes/' . $reportaje->codigo . '/' . $datosValidados['nombre_foto']);
            $existeArchivo = file_exists($rutaArchivo);

            // Si el archivo no existe y no se ha indicado acción, redirijo a la cvista para confirmación
            if (!$existeArchivo && !$peticion->filled('accion_archivo')) {
                return view('administracion.confirmaciones.confirmarregistrarfoto', [
                    'datosFotografia' => $datosValidados,
                    'reportaje' => $reportaje,
                    'nombre_foto' => $datosValidados['nombre_foto']
                ]);
            }

            // Si el usuario canceló, no crear nada
            if ($peticion->input('accion_archivo') === 'cancelar') {
                return redirect()->route('formnuevafotografia')
                    ->with('mensaje', 'Operación cancelada. No se ha registrado la fotografía.');
            }

            // Crear la nueva fotografía
            $fotografia = Fotografia::create([
                'nombre_foto' => $datosValidados['nombre_foto'],
                'reportaje_id' => $reportaje->id,
            ]);

            $mensajeExtra = '';
            if (!$existeArchivo && $peticion->input('accion_archivo') === 'agregar') {
                $mensajeExtra = ' (Nota: El archivo físico no existe en la carpeta de almacenamiento)';
            }

            return view('errores.exito', ['mensaje' => "Fotografía '{$fotografia->nombre_foto}' registrada correctamente en el reportaje {$reportaje->codigo}." . $mensajeExtra]);
        } catch (\Exception $excepcion) {
            return view('errores.error', ['mensaje' => 'No se ha podido crear la fotografía: ' . $excepcion->getMessage()]);
        }
    }

    /**
     * Elimina una fotografía de la base de datos y opcionalmente su archivo físico.
     *
     * @param int $id ID de la fotografía
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function borrarFotografia($id)
    {
        // Cargar la fotografía y su reportaje para verificar existencia y permisos
        $fotografia = Fotografia::with('reportaje')->find($id);        
        if (!$fotografia) {
            return view('errores.error', ['mensaje' => "No se ha encontrado la fotografía con ID: {$id}"]);
        }
        $reportaje = $fotografia->reportaje;
        if (!$reportaje) {
            return view('errores.error', ['mensaje' => "No se ha encontrado el reportaje asociado a la fotografía."]);
        }

        // Verificar si existe el archivo en storage
        $rutaArchivo = storage_path('app/private/fotosreportajes/' . $reportaje->codigo . '/' . $fotografia->nombre_foto);
        $existeArchivo = file_exists($rutaArchivo);

        // Si el archivo no existe, eliminar directamente de la BD
        if (!$existeArchivo) {
         
                $nombreFoto = $fotografia->nombre_foto;
                $codigoReportaje = $reportaje->codigo;
                $fotografia->delete();

                return view('errores.exito', [
                    'mensaje' => "El archivo '{$nombreFoto}' no existe en la carpeta de almacenamiento {$codigoReportaje}. Se ha eliminado la entrada de la base de datos."
                ]);
        }

        // Si el archivo existe y no se ha indicado acción, mostrar confirmación
        if (!request()->filled('accion_archivo')) {
            return view('administracion.confirmaciones.confirmarborrarfoto', [
                'fotografia' => $fotografia,
                'reportaje' => $reportaje
            ]);
        }

        // Si el usuario canceló, no eliminar nada
        if (request()->input('accion_archivo') === 'cancelar') {
            return redirect()->route('listarfotografias')
                ->with('mensaje', 'Operación cancelada. No se ha eliminado la fotografía.');
        }

        // Eliminar archivo si se eligió esa opción
        if (request()->input('accion_archivo') === 'borrar_archivo') {
            try {
                unlink($rutaArchivo);

                // Y también eliminar el thumbnail si existe
                $rutaThumbnail = storage_path('app/private/fotosreportajes/' . $reportaje->codigo . '/thumbs/' . $fotografia->nombre_foto);
                if (file_exists($rutaThumbnail)) {
                    unlink($rutaThumbnail);
                }
            } catch (\Exception $e) {
                return view('errores.error', ['mensaje' => 'Error al eliminar el archivo: ' . $e->getMessage()]);
            }
        }

        // Eliminar la fotografía de la BD
        try {
            $nombreFoto = $fotografia->nombre_foto;
            $codigoReportaje = $reportaje->codigo;
            $eliminado = $fotografia->delete();

            if ($eliminado) {
                $mensajeExtra = '';
                if (request()->input('accion_archivo') === 'borrar_archivo') {
                    $mensajeExtra = " El archivo '{$nombreFoto}' ha sido eliminado de la carpeta {$codigoReportaje}.";
                } elseif (request()->input('accion_archivo') === 'conservar_archivo') {
                    $mensajeExtra = " El archivo '{$nombreFoto}' ha sido conservado en la carpeta {$codigoReportaje}.";
                }
                return view('errores.exito', ['mensaje' => "Fotografía '{$nombreFoto}' eliminada con éxito." . $mensajeExtra]);
            }
        } catch (\Exception $excepcion) {
            return view('errores.error', ['mensaje' => "Error al eliminar la fotografía: " . $excepcion->getMessage()]);
        }

        return view('errores.error', ['mensaje' => "No se ha podido eliminar la fotografía con ID: {$id}"]);
    }

    /**
     * Procesa el formulario de edición de una fotografía.
     *
     * Verifica conflictos de nombre, existencia del archivo y permite renombrar
     * tanto en disco como en la base de datos según 'accion_archivo'.
     *
     * @param \Illuminate\Http\Request $peticion
     * @param int $id ID de la fotografía
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function procesarFormEditarFotografia(Request $peticion, $id)
    {
        // Cargar la fotografía y su reportaje para verificar existencia y permisos
        $fotografia = Fotografia::with('reportaje')->find($id);
        if (!$fotografia) {
            return view('errores.error', ['mensaje' => "No se ha encontrado la fotografía con ID: {$id}"]);
        }
        $reportaje = $fotografia->reportaje;
        if (!$reportaje) {
            return view('errores.error', ['mensaje' => "No se ha encontrado el reportaje asociado a la fotografía."]);
        }

        // Validar los datos recibidos
        $datosValidados = $peticion->validate([
            'nombre_foto' => 'required|string|max:100',
            'accion_archivo' => 'nullable|in:renombrar,no_renombrar,cancelar'
        ], [
            'nombre_foto.required' => 'El nombre de la fotografía es obligatorio.',
            'nombre_foto.string' => 'El nombre debe ser texto.',
            'nombre_foto.max' => 'El nombre no debe exceder los :max caracteres.',
        ]);

        // Verificar si se está cambiando el nombre
        $nombreAntiguo = $fotografia->nombre_foto;
        $nombreNuevo = $datosValidados['nombre_foto'];
        $cambiaNombre = ($nombreAntiguo !== $nombreNuevo);

        if (!$cambiaNombre) {
            return view('errores.exito', ['mensaje' => 'No se han realizado cambios.']);
        }

        // Verificar si ya existe una fotografía con el nuevo nombre en el mismo reportaje
        $fotoExistente = Fotografia::where('reportaje_id', $reportaje->id)
            ->where('nombre_foto', $nombreNuevo)
            ->where('id', '!=', $id)
            ->first();

        if ($fotoExistente) {
            return view('errores.error', ['mensaje' => "Ya existe una fotografía con el nombre '{$nombreNuevo}' en el reportaje {$reportaje->codigo}"]);
        }

        // Verificar si existe el archivo físico antiguo
        $rutaArchivoAntiguo = storage_path('app/private/fotosreportajes/' . $reportaje->codigo . '/' . $nombreAntiguo);
        $existeArchivo = file_exists($rutaArchivoAntiguo);

        // Si el archivo no existe, renombrar directamente en BD
        if (!$existeArchivo) {           
                $fotografia->nombre_foto = $nombreNuevo;
                $fotografia->save();

                return view('errores.exito', [
                    'mensaje' => "El archivo '{$nombreAntiguo}' no existe en la carpeta de almacenamiento {$reportaje->codigo}. Se ha renombrado la entrada de la base de datos."
                ]);          
        }

        // Si el archivo existe y no se ha indicado acción, mostrar confirmación
        if (!$peticion->filled('accion_archivo')) {
            return view('administracion.confirmaciones.confirmarrenombrarfoto', [
                'fotografia' => $fotografia,
                'reportaje' => $reportaje,
                'nombreNuevo' => $nombreNuevo
            ]);
        }

        // Si el usuario canceló, no modificar nada
        if ($peticion->input('accion_archivo') === 'cancelar') {
            return redirect()->route('reportajefotos', $reportaje->id)
                ->with('mensaje', 'Operación cancelada. No se ha renombrado la fotografía.');
        }

        // Renombrar archivo si se eligió esa opción
        if ($peticion->input('accion_archivo') === 'renombrar') {
            $rutaArchivoNuevo = storage_path('app/private/fotosreportajes/' . $reportaje->codigo . '/' . $nombreNuevo);

            if (file_exists($rutaArchivoNuevo)) {
                return view('errores.error', ['mensaje' => "Ya existe un archivo con el nombre '{$nombreNuevo}' en la carpeta {$reportaje->codigo}"]);
            }

            try {
                rename($rutaArchivoAntiguo, $rutaArchivoNuevo);

                // También renombrar el thumbnail si existe
                $rutaThumbnailAntiguo = storage_path('app/private/fotosreportajes/' . $reportaje->codigo . '/thumbs/' . $nombreAntiguo);
                $rutaThumbnailNuevo = storage_path('app/private/fotosreportajes/' . $reportaje->codigo . '/thumbs/' . $nombreNuevo);

                if (file_exists($rutaThumbnailAntiguo)) {
                    rename($rutaThumbnailAntiguo, $rutaThumbnailNuevo);
                }
            } catch (\Exception $e) {
                return view('errores.error', ['mensaje' => 'Error al renombrar el archivo: ' . $e->getMessage()]);
            }
        }

        // Actualizar en la base de datos
        try {
            $fotografia->nombre_foto = $nombreNuevo;
            $fotografia->save();

            $mensajeExtra = '';
            if ($peticion->input('accion_archivo') === 'renombrar') {
                $mensajeExtra = " El archivo ha sido renombrado en la carpeta {$reportaje->codigo}.";
            } elseif ($peticion->input('accion_archivo') === 'no_renombrar') {
                $mensajeExtra = " El archivo '{$nombreAntiguo}' ha sido conservado con su nombre original.";
            }

            return view('errores.exito', ['mensaje' => "Fotografía renombrada de '{$nombreAntiguo}' a '{$nombreNuevo}' con éxito." . $mensajeExtra]);
        } catch (\Exception $excepcion) {
            return view('errores.error', ['mensaje' => "Error al renombrar la fotografía: " . $excepcion->getMessage()]);
        }
    }

    /**
     * Filtra fotografías públicas por etiqueta y devuelve la galería pública.
     *
     * @param \Illuminate\Http\Request $peticion
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function filtrarPorEtiqueta(Request $peticion)
    {
        $busqueda = $peticion->input('busqueda');

        if (!$busqueda) {
            return redirect()->route('fotospublicas');
        }
        // Convertir la búsqueda a mayúsculas
        $busqueda = strtoupper($busqueda);
            // Buscar fotografías que tengan la etiqueta buscada y cuyo reportaje sea público
            $fotografias = Fotografia::whereHas('etiquetas', function ($query) use ($busqueda) {
                $query->where('nombre_etiqueta', $busqueda);
            })->whereHas('reportaje', function ($query) {
                $query->where('publico', true);
            })->paginate(9);

            // Obtener reportajes marcados como públicos para mostrar en la galería
            $colecciones = Reportaje::where('publico', true)->get();
      
        // Si la petición es AJAX, devolver solo la vista de la galería para actualizar dinámicamente
        // (Esto es para que el filtro por etiqueta funcione sin recargar toda la página)
        if ($peticion->ajax()) {
            return view('parciales.paginadofotospublicas', ['fotografias' => $fotografias]);
        }

        $nombresEtiquetas = \App\Models\Etiqueta::pluck('nombre_etiqueta')->toArray();

        return view('zonapublica.galeriapublica', [
            'fotografias' => $fotografias,
            'colecciones' => $colecciones,
            'nombresEtiquetas' => $nombresEtiquetas,
            'mensaje' => "Resultados de búsqueda para la etiqueta: '{$busqueda}'"
        ]);
    }

    /**
     * Busca "fotos fantasma": entradas en la base de datos cuyo archivo físico
     * ya no existe en storage.
     *
     * Devuelve una paginación MANUAL sobre la colección filtrada.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function buscarFotosFantasma(Request $request)
    {
        // Petición normal (no Ajax): mostrar la página con el spinner; el contenido se carga vía AJAX
        if (!$request->ajax()) {
            return view('parciales.listados.listarfotografiasfantasma');
        }
        // Petición AJAX: realizar la búsqueda y devolver solo vista tabla con los resultados.
        $rutaBase = storage_path('app/private/fotosreportajes');

        // Obtener todos los reportajes de la BD
        $todasLasFotos = Fotografia::all();

        // Filtrar las que NO tienen archivo físico asociado
        $fantasmas = $todasLasFotos->filter(function ($foto) use ($rutaBase) {
            if (!$foto->reportaje) {
                return true; // Sin reportaje asociado → también es fantasma
            }
            $rutaArchivo = $rutaBase . DIRECTORY_SEPARATOR . $foto->reportaje->codigo . DIRECTORY_SEPARATOR . $foto->nombre_foto;
            return !file_exists($rutaArchivo);
        });

        $total = $fantasmas->count();

        // Paginación manual sobre la colección (paginate() no existe en Collection)
        $paginaActual = LengthAwarePaginator::resolveCurrentPage();
        $porPagina = 15;
        $itemsPagina = $fantasmas->values()->slice(($paginaActual - 1) * $porPagina, $porPagina);
        $fotografias = new LengthAwarePaginator(
            $itemsPagina,
            $total,
            $porPagina,
            $paginaActual,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        $mensaje = $total > 0 ? "Fotos fantasma encontradas: {$total}" : "No se han encontrado fotos fantasma.";
        return view('parciales.listados.tablafotosfantasma', compact('fotografias', 'mensaje'));
    }
}
