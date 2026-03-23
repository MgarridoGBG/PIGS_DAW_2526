<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Reportaje
 *
 * Representa un reportaje fotográfico. Campos principales:
 * - 'tipo', 'codigo', 'descripcion', 'fecha_report', 'user_id', 'publico'.
 *
 * NOTA: El codigo de cada reportaje es único, y responde a la plantilla YYYYMMDDREPORXXXX donde XXXX es número de orden
 * se basa en la convención del estudio a la hora de almacenar las fotografías, y es el nombre de la
 * carpeta donde se guardan las fotos de cada reportaje. No lo ajusto a una expersíon regular porque en ocasiones
 * el estudio cambia esta dinámica.
 */
class Reportaje extends Model
{
    use HasFactory;
    /** Campos asignables */
    protected $fillable = ['tipo','codigo','descripcion','fecha_report','user_id','publico'];

    /**
     * Relación: un reportaje pertenece a un usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: un reportaje tiene muchas fotografías.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fotografias()
    {
        return $this->hasMany(Fotografia::class);
    }
}
