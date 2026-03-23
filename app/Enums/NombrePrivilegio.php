<?php

namespace App\Enums;
/**
 * Enum NombrePrivilegio
 *
 * Representa los privilegios posibles de un role en la aplicación.
 * Se usa de forma transversal para mantener consistencia en la base de datos y en la lógica de negocio.
 *
 * El método estático 'values()' devuelve los valores como un array de strings
 * para su uso en validaciones y formularios.
 */
enum NombrePrivilegio: string
{
    case EDITAR_PROPIO = 'editar_propio';
    case CONCERTAR_CITA = 'concertar_cita';
    case HACER_PEDIDO = 'hacer_pedido';
    case ADMIN_BASICO = 'admin_basico';
    case ADMIN_AVANZADO = 'admin_avanzado';

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