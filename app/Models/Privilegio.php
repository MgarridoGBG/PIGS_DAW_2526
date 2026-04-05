<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Privilegio
 *
 * Representa un privilegio que puede asignarse a roles. Relación
 * muchos-a-muchos con 'Role' a través de la tabla pivote 'roles_privilegios'.
 */
class Privilegio extends Model
{
    use HasFactory;

    /** Campos asignables */
    protected $fillable = ['nombre_priv'];

    /**
     * Relación: un privilegio puede pertenecer a muchos roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'roles_privilegios')
            ->withTimestamps();
    }
}
