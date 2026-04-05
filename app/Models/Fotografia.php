<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Fotografia
 *
 * Representa una fotografía vinculada a un reportaje y que puede tener
 * múltiples etiquetas e items que la utilicen.
 */
class Fotografia extends Model
{
    use HasFactory;

    /** Campos asignables */
    protected $fillable = ['nombre_foto', 'reportaje_id'];

    /**
     * Relación: una fotografía pertenece a un solo reportaje.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reportaje()
    {
        return $this->belongsTo(Reportaje::class);
    }

    /**
     * Relación: una fotografía puede tener muchas etiquetas.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function etiquetas()
    {
        return $this->belongsToMany(Etiqueta::class, 'fotografias_etiquetas')
            ->withTimestamps();
    }

    /**
     * Relación: una fotografía puede ser usada en muchos items.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
