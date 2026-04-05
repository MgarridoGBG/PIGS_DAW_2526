<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Role
 *
 * Representa un rol de usuario usando el ENUM NombreRoles y
 * define relaciones con usuarios y privilegios.
 */
class Role extends Model
{
    use HasFactory;

    /** Campos asignables */
    protected $fillable = ['nombre_role'];

    /**
     * Relación: un rol tiene muchos usuarios.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relación: un rol puede tener muchos privilegios (pivot `roles_privilegios`).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function privilegios()
    {
        return $this->belongsToMany(Privilegio::class, 'roles_privilegios')->withTimestamps();
    }
}
