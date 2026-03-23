<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Pedido
 *
 * Representa un pedido realizado por un usuario y compuesto por varios items.
 */
class Pedido extends Model
{
    use HasFactory;
    /** Campos asignables */
    protected $fillable = ['estado_pedido','fecha_pedido','user_id'];

    /**
     * Relación: un pedido pertenece a un usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: un pedido tiene muchos items.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
