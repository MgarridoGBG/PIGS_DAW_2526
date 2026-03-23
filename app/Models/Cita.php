<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Cita
 *
 * Representa una cita asociada a un usuario. Con 'fecha_cita', 'turno' (usar 'TurnoCita' enum),
 *  'estado_cita' (usar 'EstadoCita' enum) y 'user_id' usuario que solicita la cita.
 *
 * 'fecha_cita' se castea a 'Y-m-d' para facilitar las comparaciones y formato.
 */
class Cita extends Model
{
    use HasFactory;
    /** Campos asignables masivamente */
    protected $fillable = ['fecha_cita', 'turno', 'estado_cita', 'user_id'];

    /** Casts para atributos */
    protected $casts = ['fecha_cita' => 'date:Y-m-d',]; 

    /**
     * Relación: una cita pertenece a un usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
