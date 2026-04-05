<?php

/**
 * Enum EstadoPedido
 *
 * Representa los estados posibles de un pedido en la aplicación.
 * Se usa de forma transversal para mantener consistencia en la base de datos y en la lógica de negocio.
 *
 * El método estático 'values()' devuelve los valores como un array de strings
 * para su uso en validaciones y formularios.
 */

namespace App\Enums;

enum EstadoPedido: string
{
    case EMITIDO = 'emitido';
    case PRESUPUESTADO = 'presupuestado';
    case ACEPTADO = 'aceptado';
    case PAGADO = 'pagado';
    case ENVIADO = 'enviado';
    case CERRADO = 'cerrado';

    /**
     * Devuelve los valores del enum como array de strings.
     *
     * @return string[]
     */
    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
