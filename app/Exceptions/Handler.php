<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

/**
 * Manejador de excepciones de la aplicación.
 *
 * Centraliza el manejo de excepciones para la aplicación. Se extiende la
 * clase  de Laravel ExceptionHandler y se sobreescribe el método
 * render para devolver vistas de error personalizadas y amigables para el usuario.
 *
 */

class Handler extends ExceptionHandler
{
  

    public function render($peticion, Throwable $excepcion)
    {
        // Manejo de MethodNotAllowedHttpException: cuando se solicita
        // una ruta con un metodo HTTP no permititod (PUT, DELETE, etc.)
        if ($excepcion instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
            return response()->view(
                'errores.error',
                ['mensaje' => 'Método HTTP no permitido para esta ruta.'],
                405
            );
        }

        // Manejo global de errores de base de datos (QueryException / PDOException)
        // Para evitar exponer detalles internos devolvemos una vista genérica
        // y código 500.
        if ($excepcion instanceof \Illuminate\Database\QueryException || $excepcion instanceof \PDOException) {
            return response()->view(
                'errores.error',
                ['mensaje' => 'La base de datos ha generado un error.'],
                500
            );
        }

        // Por defecto, comportamiento base de Laravel
        return parent::render($peticion, $excepcion);
    }
}
