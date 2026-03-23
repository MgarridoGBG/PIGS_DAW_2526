<?php

namespace Tests\Feature;

use App\Enums\NombreRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PruebasUserTest extends TestCase
{
    use RefreshDatabase;

    // Helpers 

    //Crea un usuario con un role determinado por nombre usando su factory.
    private function crearUsuarioConRole(string $nombreRole): User
    {
        $role = Role::factory()->create(['nombre_role' => $nombreRole]);
        return User::factory()->create(['role_id' => $role->id]);
    }

    //Datos válidos para crear un usuario nuevo.
    private function DatosUsuarioValidos(array $sobreescribir = []): array
    {
        return array_merge([
            'nombre'               => 'Usuario',
            'apellidos'            => 'De Prueba',
            'email'                => 'userdeprueba@opta.com',
            'telefono'             => '612345678',
            'direccion'            => 'Calle de prueba 1',
            'dni'                  => '12345678Z',
            'password'             => 'Password1',
            'password_confirmation' => 'Password1',
        ], $sobreescribir);
    }

        // borrarUsuario

    /**
     * Borrar un usuario con ID inexistente devuelve la vista errores.error.
     */
    public function testFalloBorrarIDNoExiste(): void
    {
        $respuesta = $this->withoutMiddleware()
            ->delete(route('borrarusuario', 99999));

        $respuesta->assertStatus(200);
        $respuesta->assertViewIs('errores.error');
    }

    /**
     * Borrar un usuario existente lo elimina y devuelve la vista errores.exito.
     */
    public function testExitoBorraUsuario(): void
    {
        $usuario = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);

        $respuesta = $this->withoutMiddleware()
            ->delete(route('borrarusuario', $usuario->id));

        $respuesta->assertStatus(200);
        $respuesta->assertViewIs('errores.exito');
        $this->assertDatabaseMissing('users', ['id' => $usuario->id]);
    }

        // procesarFormEditarUsuario

    /**
     * nombre con más de 50 caracteres falla la validación.
     */
    public function testFalloNombreLargo(): void
    {
        $usuario = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);

        $respuesta = $this->withoutMiddleware()
            ->put(route('editarusuario', $usuario->id), [
                'nombre' => str_repeat('a', 51),
            ]);

        $respuesta->assertSessionHasErrors(['nombre']);
    }

    /**
     * email con formato inválido falla la validación.
     */
    public function testFalloEmailInvalido(): void
    {
        $usuario = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);

        $respuesta = $this->withoutMiddleware()
            ->put(route('editarusuario', $usuario->id), [
                'email' => 'no-es-un-email',
            ]);

        $respuesta->assertSessionHasErrors(['email']);
    }

    /**
     * email ya usado por otro usuario falla la validación.
     */
    public function testFalloEmailDupe(): void
    {
        $usuarioA = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);
        $usuarioB = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);

        $respuesta = $this->withoutMiddleware()
            ->put(route('editarusuario', $usuarioB->id), [
                'email' => $usuarioA->email,
            ]);

        $respuesta->assertSessionHasErrors(['email']);
    }

    /**
     * DNI con formato inválido falla la validación.
     */
    public function testFalloDniInvalido(): void
    {
        $usuario = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);

        $respuesta = $this->withoutMiddleware()
            ->put(route('editarusuario', $usuario->id), [
                'dni' => 'INVALIDO!!',
            ]);

        $respuesta->assertSessionHasErrors(['dni']);
    }

    /**
     * DNI ya usado por otro usuario falla la validación.
     */
    public function testFalloDniDupe(): void
    {
        $usuarioA = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);
        $usuarioB = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);

        $respuesta = $this->withoutMiddleware()
            ->put(route('editarusuario', $usuarioB->id), [
                'dni' => $usuarioA->dni, // Intentamos poner el mismo DNI que usuarioA
            ]);

        $respuesta->assertSessionHasErrors(['dni']);
    }

    /**
     * password sin confirmación falla la validación.
     */
    public function testFalloPasswordSinConfirmacion(): void
    {
        $usuario = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);

        $respuesta = $this->withoutMiddleware()
            ->put(route('editarusuario', $usuario->id), [
                'password'              => 'Password1',
                'password_confirmation' => 'OtraDistinta1',
            ]);

        $respuesta->assertSessionHasErrors(['password']);
    }

    /**
     * password iguales que no cumple el regex (sin mayúscula) falla la validación.
     */
    public function testFalloPasswordRegex(): void
    {
        $usuario = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);

        $respuesta = $this->withoutMiddleware()
            ->put(route('editarusuario', $usuario->id), [
                'password'              => 'password1',
                'password_confirmation' => 'password1',
            ]);

        $respuesta->assertSessionHasErrors(['password']);
    }

    /**
     * Datos correctos actualizan el usuario y devuelven la vista errores.exito.
     */
    public function testExitoEditarUsuario(): void
    {
        $usuario = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);

        $respuesta = $this->withoutMiddleware()
            ->put(route('editarusuario', $usuario->id), [
                'nombre' => 'NuevoNombre',
            ]);

        $respuesta->assertStatus(200);
        $respuesta->assertViewIs('errores.exito');
        $this->assertDatabaseHas('users', ['id' => $usuario->id, 'nombre' => 'NuevoNombre']);
    }

        // filtrarUsuarios

    /**
     * Filtrar por nombre devuelve solo los usuarios que coinciden.
     */
    public function testFiltrarPorNombre(): void
    {
        $role = Role::factory()->create(['nombre_role' => NombreRole::CLIENTE->value]);
        User::factory()->create(['nombre' => 'Alberto', 'role_id' => $role->id]);
        User::factory()->create(['nombre' => 'Alberto Cruz', 'role_id' => $role->id]);
        User::factory()->create(['nombre' => 'Beatriz', 'role_id' => $role->id]);

        $respuesta = $this->withoutMiddleware()
            ->get(route('filtrarusuarios', ['nombre' => 'Alberto']));

        $respuesta->assertStatus(200);
        $respuesta->assertViewIs('parciales.listados.listausuarios');

        $usuariosFiltrados = $respuesta->viewData('usuarios');
        $this->assertCount(2, $usuariosFiltrados);
        foreach ($usuariosFiltrados as $usuario) {
            $this->assertStringContainsStringIgnoringCase('Alberto', $usuario->nombre);
        }
    }

    /**
     * Filtrar por email devuelve solo los usuarios que coinciden.
     */
    public function testFiltrarPorEmail(): void
    {
        $role = Role::factory()->create(['nombre_role' => NombreRole::CLIENTE->value]);
        User::factory()->create(['email' => 'test@dominio.com', 'role_id' => $role->id]);
        User::factory()->create(['email' => 'test2@dominio.com', 'role_id' => $role->id]);
        User::factory()->create(['email' => 'otro@distinto.com', 'role_id' => $role->id]);

        $respuesta = $this->withoutMiddleware()
            ->get(route('filtrarusuarios', ['email' => 'dominio.com']));

        $respuesta->assertStatus(200);
        $usuariosFiltrados = $respuesta->viewData('usuarios');
        $this->assertCount(2, $usuariosFiltrados);
    }

    /**
     * Filtrar por rol devuelve solo los usuarios de ese rol.
     */
    public function testFiltrarPorRol(): void
    {
        $roleCliente  = Role::factory()->create(['nombre_role' => NombreRole::CLIENTE->value]);
        $roleEmpleado = Role::factory()->create(['nombre_role' => NombreRole::EMPLEADO->value]);
        User::factory()->count(2)->create(['role_id' => $roleCliente->id]);
        User::factory()->count(3)->create(['role_id' => $roleEmpleado->id]);

        $respuesta = $this->withoutMiddleware()
            ->get(route('filtrarusuarios', ['role' => NombreRole::EMPLEADO->value]));

        $respuesta->assertStatus(200);
        $usuariosFiltrados = $respuesta->viewData('usuarios');
        $this->assertCount(3, $usuariosFiltrados);
    }

        // registrarNuevoUsuario

    /**
     * nombre con más de 50 caracteres falla la validación al crear.
     */
    public function testFalloNuevoNombreLargo(): void
    {
        $respuesta = $this->withoutMiddleware()
            ->post(route('nuevousuario'), $this->datosUsuarioValidos([
                'nombre' => str_repeat('a', 51),
            ]));

        $respuesta->assertSessionHasErrors(['nombre']);
    }

    /**
     * email con formato inválido falla la validación al crear.
     */
    public function testFalloNuevoEmailInvalido(): void
    {
        $respuesta = $this->withoutMiddleware()
            ->post(route('nuevousuario'), $this->datosUsuarioValidos([
                'email' => 'correo-invalido',
            ]));

        $respuesta->assertSessionHasErrors(['email']);
    }

    /**
     * email duplicado falla la validación al crear.
     */
    public function testFalloNuevoEmailDupe(): void
    {
        $usuarioExistente = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);

        $respuesta = $this->withoutMiddleware()
            ->post(route('nuevousuario'), $this->datosUsuarioValidos([
                'email' => $usuarioExistente->email,
            ]));

        $respuesta->assertSessionHasErrors(['email']);
    }

    /**
     * DNI con formato inválido falla la validación al crear.
     */
    public function falloNuevoDniInvalido(): void
    {
        $respuesta = $this->withoutMiddleware()
            ->post(route('nuevousuario'), $this->datosUsuarioValidos([
                'dni' => 'BADFORMAT',
            ]));

        $respuesta->assertSessionHasErrors(['dni']);
    }

    /**
     * DNI duplicado falla la validación al crear.
     */
    public function testFalloNuevoDniDupe(): void
    {
        $usuarioExistente = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);

        $respuesta = $this->withoutMiddleware()
            ->post(route('nuevousuario'), $this->datosUsuarioValidos([
                'dni' => $usuarioExistente->dni,
            ]));

        $respuesta->assertSessionHasErrors(['dni']);
    }

    /**
     * password que no cumple el regex (sin número) falla la validación al crear.
     */
    public function testFalloNuevoPasswordRegex(): void
    {
        $respuesta = $this->withoutMiddleware()
            ->post(route('nuevousuario'), $this->datosUsuarioValidos([
                'password'              => 'sinNumeroNiMayus',
                'password_confirmation' => 'sinNumeroNiMayus',
            ]));

        $respuesta->assertSessionHasErrors(['password']);
    }

    /**
     * passwords que no coinciden fallan la validación al crear.
     */
    public function testFalloNuevoPasswordsNoCoinciden(): void
    {
        $respuesta = $this->withoutMiddleware()
            ->post(route('nuevousuario'), $this->datosUsuarioValidos([
                'password'              => 'Password1',
                'password_confirmation' => 'Password2',
            ]));

        $respuesta->assertSessionHasErrors(['password']);
    }

    /**
     * Datos válidos crean el usuario y devuelven la vista errores.exito.
     */
    public function testExitoNuevoUsuario(): void
    {
        Role::factory()->create(['nombre_role' => NombreRole::CLIENTE->value]);

        $respuesta = $this->withoutMiddleware()
            ->post(route('nuevousuario'), $this->datosUsuarioValidos());

        $respuesta->assertStatus(200);
        $respuesta->assertViewIs('errores.exito');
        $this->assertDatabaseHas('users', ['email' => 'userdeprueba@opta.com']);
    }

        // filtrarClientesFantasma

    /**
     * Solo devuelve clientes sin reportajes, pedidos ni citas.
     */
    public function testFiltrarClientesFantasma(): void
    {
        $roleCliente = Role::factory()->create(['nombre_role' => NombreRole::CLIENTE->value]);

        // Clientes sin ninguna relación (fantasma)
        User::factory()->count(2)->create(['role_id' => $roleCliente->id]);

        // Cliente con un reportaje asociado (no fantasma)
        $clienteConReportaje = User::factory()->create(['role_id' => $roleCliente->id]);
        \App\Models\Reportaje::factory()->create(['user_id' => $clienteConReportaje->id]);

        $respuesta = $this->withoutMiddleware()
            ->get(route('filtrarclientesfantasma'));

        $respuesta->assertStatus(200);
        $respuesta->assertViewIs('parciales.listados.listausuarios');

        $fantasmas = $respuesta->viewData('usuarios');
        $this->assertCount(2, $fantasmas);
        foreach ($fantasmas as $usuario) {
            $this->assertEquals(NombreRole::CLIENTE->value, $usuario->role->nombre_role);
        }
    }
}
