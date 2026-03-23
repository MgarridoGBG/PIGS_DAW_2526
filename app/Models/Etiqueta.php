<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Etiqueta
 *
 * Representa etiquetas aplicables a fotografías. Relación muchos-a-muchos
 * con 'Fotografia' mediante la tabla pivote 'fotografias_etiquetas'.
 */
class Etiqueta extends Model
{
    use HasFactory;
    /** Campos asignables */
    protected $fillable = ['nombre_etiqueta'];

    /**
     * Relación: una etiqueta puede pertenecer a muchas fotografías.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function fotografias()
    {
        return $this->belongsToMany(Fotografia::class, 'fotografias_etiquetas')
                    ->withTimestamps();
    }
}
