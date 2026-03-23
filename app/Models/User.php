<?php
// proyecto/app/Models/User.php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Modelo User
 *
 * Representa un usuario autenticable del sistema. Contiene relaciones con
 * 'Role', 'Reportaje', 'Pedido' y 'Cita'. También expone los privilegios
 * vía la relación de su rol.
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Atributos que son asignables masivamente.
     *
     * @var list<string>
     */
    protected $fillable = [
        'telefono',
        'email',
        'nombre',
        'apellidos',
        'direccion',
        'password',
        'dni',
        'role_id',
        'marcado_eliminar'
    ];

    /**
     * Atributos que deben ocultarse durante la serialización.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relación: un usuario tiene un rol.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relación: un usuario puede tener muchos reportajes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reportajes()
    {
        return $this->hasMany(Reportaje::class);
    }

    /**
     * Relación: un usuario puede tener muchos pedidos.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    /**
     * Relación: un usuario solo puede tener una cita.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cita()
    {
        return $this->hasOne(Cita::class);
    }

    /**
     * Obtener privilegios a través del rol del usuario.
     * Devuelve la consulta de privilegios asociada al rol.
     * Necesario para usar el middleware de autorización basado en privilegios.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function privilegios()
    {
        return $this->role->privilegios();
    }

    /**
     * Atributos que deben castear automáticamente.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'marcado_eliminar' => 'boolean',
    ];
}
