<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChequeaPrivilegio
{

    /**
     * Middleware para comprobar si el usuario autenticado posee un privilegio.
     *
     * Uso en rutas: ->middleware('chequea_privilegio:nombre_privilegio')
     *    
     * - Si no hay usuario autenticado redirige a la ruta 'noautenticado'.
     * - Si el usuario no tiene el privilegio indicado redirige a 'accesodenegado'.
     * - Si todo OK, continúa con la petición.
     * 
     * Asume la existencias de la relación 'privilegios' en el modelo User que devuelve
     * los privilegios del rol del usuario.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $privilegio Nombre del privilegio requerido
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, $privilegio)
    {
        $user = $request->user();

        // Verificar si el usuario está autenticado o redirigir a noautenticado
        if (!$user) {
            return redirect()->route('noautenticado');
        }

        // Verificar si el usuario tiene el privilegio solicitado o redirigir a acceso denegado
        if (!$user->privilegios()->where('nombre_priv', $privilegio)->exists()) {
            return redirect()->route('accesodenegado');
        }

        return $next($request);
    }
}
