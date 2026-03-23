<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Soporte
 *
 * Representa un material o soporte físico con precio que puede aplicarse
 * a items. Incluye disponibilidad y precio unitario.
 */
class Soporte extends Model
{
    use HasFactory;
    /** Campos asignables */
    protected $fillable = ['nombre_soport','disponibilidad','precio'];

    /**
     * Relación: un soporte puede estar presente en muchos items.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}

