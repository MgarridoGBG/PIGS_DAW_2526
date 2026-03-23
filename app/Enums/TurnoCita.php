<?php

namespace App\Enums;

/**
 * Enum TurnoCita
 *
 * Representa los turnos posibles de una cita en la aplicación.
 * Se usa de forma transversal para mantener consistencia en la base de datos y en la lógica de negocio.
 *
 * El método estático 'values()' devuelve los valores como un array de strings
 * para su uso en validaciones y formularios.
 */
enum TurnoCita: string
{
    case MANANA = 'mañana';
    case TARDE = 'tarde';

    /**
     * Devuelve los valores del enum como array de strings.
     *
     * @return string[]
     */

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
