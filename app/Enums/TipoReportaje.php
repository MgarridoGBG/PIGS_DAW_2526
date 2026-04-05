<?php

namespace App\Enums;

/**
 * Enum TipoReportaje
 *
 * Representa los tipos posibles de un reportaje en la aplicación.
 * Se usa de forma transversal para mantener consistencia en la base de datos y en la lógica de negocio.
 *
 * El método estático 'values()' devuelve los valores como un array de strings
 * para su uso en validaciones y formularios.
 */
enum TipoReportaje: string
{
    case PUBLICITARIO = 'publicitario';
    case BOOK = 'book';
    case INFANTIL = 'infantil';
    case PRODUCTO = 'producto';
    case MODA = 'moda';
    case PAISAJE = 'paisaje';
    case OTRO = 'otro';
    case GALERIA = 'galeria';

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
