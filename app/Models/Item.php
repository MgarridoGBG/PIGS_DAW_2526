<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Item
 *
 * Representa una línea de pedido que referencia una fotografía,
 * con  formato y soporte, e incluye cantidad y precio unitario.
 */
class Item extends Model
{
    use HasFactory;

    /** Campos asignables */
    protected $fillable = ['cantidad', 'pedido_id', 'formato_id', 'soporte_id', 'fotografia_id', 'precio'];

    /**
     * Relación: un item pertenece a un solo pedido.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    /**
     * Relación: un item tiene un formato.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function formato()
    {
        return $this->belongsTo(Formato::class);
    }

    /**
     * Relación: un item tiene un soporte.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function soporte()
    {
        return $this->belongsTo(Soporte::class);
    }

    /**
     * Relación: un item usa una fotografía.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fotografia()
    {
        return $this->belongsTo(Fotografia::class);
    }
}
