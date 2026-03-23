<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Formato
 *
 * Representa un formato físico o digital (ancho x alto) que puede aplicarse
 * a items de pedidos. Contiene relaciones con 'Item'.
 */
class Formato extends Model
{
    use HasFactory;
    /** Campos asignables */
    protected $fillable = ['nombre_format','ancho','alto'];

    /**
     * Relación: un formato puede estar en muchos items.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
