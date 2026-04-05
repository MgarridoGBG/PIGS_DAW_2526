<?php

namespace App\Enums;

/**
 * Enum ExtensionesFotos
 *
 * Representa las extensiones de los archivos de fotos que se pueden usar y manejar en la aplicación.
 * Se utiliza para validar las extensiones de los archivos al añadir fotos a los reportajes.
 *
 * El método estático 'values()' devuelve los valores como un array de strings.
 */
enum ExtensionesFotos: string
{
    case JPG = 'jpg';
    case JPEG = 'jpeg';
    case WEBP = 'webp';
    case SVG = 'svg';
    case PNG = 'png';
    case GIF = 'gif';
    case BMP = 'bmp';
    case TIFF = 'tiff';
    case PSD = 'psd';
    case RAW = 'raw';

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
