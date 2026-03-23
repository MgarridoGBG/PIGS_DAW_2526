<?php

namespace Tests\Feature;

use App\Enums\NombreRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PruebasZonaPrivTest extends TestCase
{
    use RefreshDatabase;

    /** Crea un usuario con un role determinado por nombre. */
    private function crearUsuarioConRole(string $nombreRole): User
    {
        $role = Role::factory()->create(['nombre_role' => $nombreRole]);
        return User::factory()->create(['role_id' => $role->id]);
    }

    // Acceso a /zonaprivada sin autenticar
  
    /**
     * Un usuario NO autenticado que accede a /zonaprivada
     * es redirigido a /login (status 302).
     */
    public function testNoAutenticadoRedirige(): void
    {
        $respuesta = $this->get(route('zonaprivada'));

        $respuesta->assertStatus(302);
        $respuesta->assertRedirect('/login');
    }

    /**
     * Un usuario NO autenticado que sigue la redirección
     * llega al formulario de login (status 200).
     */
    public function testNoAutenticadoLlegaALogin(): void
    {
        $respuesta = $this->followingRedirects()->get(route('zonaprivada'));

        $respuesta->assertStatus(200);
        $respuesta->assertViewIs('auth.login');
    }

    // Acceso a /zonaprivada autenticado
    
    /**
     * Un usuario autenticado accede correctamente
     * a la zona privada (status 200, vista zonaprivada.privada).
     */
    public function testAutenticadoAccede(): void
    {
        $usuario = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);

        $respuesta = $this->actingAs($usuario)->get(route('zonaprivada'));

        $respuesta->assertStatus(200);
        $respuesta->assertViewIs('zonaprivada.privada');
    }

    /**
     * La zona privada carga los datos del usuario autenticado
     * y los pasa a la vista correctamente.
     */
    public function testZonaPrivadaRecibeDatosUser(): void
    {
        $usuario = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);

        $respuesta = $this->actingAs($usuario)->get(route('zonaprivada'));

        $respuesta->assertStatus(200);
        $respuesta->assertViewHas('usuario', function ($usuarioVista) use ($usuario) {
            return $usuarioVista->id === $usuario->id;
        });
        $respuesta->assertViewHas('reportajes');
        $respuesta->assertViewHas('pedidos');
        $respuesta->assertViewHas('citas');
    }
}
