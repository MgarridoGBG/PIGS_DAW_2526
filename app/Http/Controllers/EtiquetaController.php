<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Etiqueta;
use App\Models\Fotografia;

/**
 * Controlador para gestionar las etiquetas de fotografías.
 *
 * Proporciona operaciones para crear, borrar y asociar/desasociar etiquetas a fotografias.
 *
 */
class EtiquetaController extends Controller
{
    /**
     * Añade una etiqueta a una fotografía.
     *     
     * @param  \Illuminate\Http\Request  $peticion
     * @param  int  $id  Identificador de la fotografía
     * @return \Illuminate\Contracts\View\View
     */
    public function anadirEtiquetaFoto(Request $peticion, $id)
    {
        // Obtener nombre_etiqueta, pasar a mayúsculas y comprobar si ya existe.
        $nombre_etiqueta = $peticion->input('nombre_etiqueta');
        $nombre_etiqueta = strtoupper($nombre_etiqueta);
        $etiqueta = Etiqueta::where('nombre_etiqueta', $nombre_etiqueta)->first();

        // Buscar la fotografía y comprobar si existe.
        $fotografia = Fotografia::find($id);

        if (!$fotografia) {
            return view('errores.error', ['mensaje' => 'La fotografía no existe']);
        }

        if (!$etiqueta) {
            // Si no existe la etiqueta,la creamos y la asociamos a la fotografía (Éxito).
            $etiqueta = Etiqueta::create([
                'nombre_etiqueta' => $nombre_etiqueta
            ]);

            $fotografia->etiquetas()->attach($etiqueta->id);

            return view('errores.exito', ['mensaje' => "Se ha creado la etiqueta {$nombre_etiqueta} y se ha añadido a la foto {$id}"]);
        } else {

            // Comprobar si la fotografía ya tiene asociada esa etiqueta (Error) y si no la tiene la asociamos (Éxito).
            if ($fotografia->etiquetas()->where('etiqueta_id', $etiqueta->id)->exists()) {
                return view('errores.error', ['mensaje' => 'La fotografía ya tiene esta etiqueta asociada']);
            }
           
            $fotografia->etiquetas()->attach($etiqueta->id);
            return view('errores.exito', ['mensaje' => 'Etiqueta añadida']);
        }

        return view('errores.exito', ['mensaje' => "Se ha creado la etiqueta {$nombre_etiqueta} y se ha añadido a la foto {$id}"]);
    }

    /**
     * Elimina la asociación de una etiqueta con una fotografía.
     *     
     * @param  \Illuminate\Http\Request  $peticion
     * @param  int  $id  Identificador de la fotografía
     * @return \Illuminate\Contracts\View\View
     */
    public function borrarEtiquetaFoto(Request $peticion, $id)
    {
        // Obtener nombre_etiqueta, pasar a mayúsculas.
        $nombre_etiqueta = $peticion->input('nombre_etiqueta');
        $nombre_etiqueta = strtoupper($nombre_etiqueta);

        // Buscar la fotografía
        $fotografia = Fotografia::find($id);

        if (!$fotografia) {
            return view('errores.error', ['mensaje' => 'La fotografía no existe']);
        }

        // Comprobar si existe una etiqueta con ese nombre (Error), comprobar si la fotografía no la tiene asociada (Error) y si la tiene desasociarla (Éxito).
        $etiqueta = Etiqueta::where('nombre_etiqueta', $nombre_etiqueta)->first();

        if (!$etiqueta) {
            return view('errores.error', ['mensaje' => 'La etiqueta no existe']);
        }
        
        if (!$fotografia->etiquetas()->where('etiqueta_id', $etiqueta->id)->exists()) {
            return view('errores.error', ['mensaje' => 'La fotografía no tiene esta etiqueta asociada']);
        }
        
        $fotografia->etiquetas()->detach($etiqueta->id);

        return view('errores.exito', ['mensaje' => 'Etiqueta eliminada']);
    }

    /**
     * Elimina una etiqueta del sistema por su nombre.
     *
     * @param  \Illuminate\Http\Request  $peticion
     * @return \Illuminate\Contracts\View\View
     */
    public function borrarEtiqueta(Request $peticion)
    {
        $nombre_etiqueta = $peticion->input('nombre_etiqueta');       
        $nombre_etiqueta = strtoupper($nombre_etiqueta);

        // Buscar la etiqueta por su nombre (en mayúsculas) y eliminarla si existe.
        $etiqueta = Etiqueta::where('nombre_etiqueta', $nombre_etiqueta)->first();

        if ($etiqueta) {
            $etiqueta->delete();
            return view('errores.exito', [
                'mensaje' => "Etiqueta {$nombre_etiqueta} eliminada"
            ]);
        }

        return view('errores.error', ['mensaje' => 'No existe la etiqueta']);
    }

    /**
     * Crea una nueva etiqueta si no existe.
     *
     * @param  \Illuminate\Http\Request  $peticion
     * @return \Illuminate\Contracts\View\View
     */
    public function crearEtiqueta(Request $peticion)
    {

        $nombre_etiqueta = $peticion->input('nombre_etiqueta');

        // Convertir nombre_etiqueta a mayúsculas
        $nombre_etiqueta = strtoupper($nombre_etiqueta);

        // Buscar la etiqueta. Si no existe, crearla (Éxito). Si existe, mostrar error.
        $etiqueta = Etiqueta::where('nombre_etiqueta', $nombre_etiqueta)->first();

        if (!$etiqueta) {
            $etiqueta = Etiqueta::create([
                'nombre_etiqueta' => $nombre_etiqueta
            ]);
            return view('errores.exito', [
                'mensaje' => "Etiqueta {$nombre_etiqueta} creada"
            ]);

        }
            return view('errores.error', ['mensaje' => 'Ya existe una etiqueta con ese nombre']);
        
    }
}
