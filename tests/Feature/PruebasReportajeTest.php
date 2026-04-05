<?php

namespace Tests\Feature;

use App\Enums\NombreRole;
use App\Enums\TipoReportaje;
use App\Http\Controllers\ReportajeController;
use App\Models\Reportaje;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PruebasReportajeTest extends TestCase
{
    use RefreshDatabase;

    // Helper privado para crear un usuario con un role específico

    private function crearUsuarioConRole(string $nombreRole): User
    {
        $role = Role::factory()->create(['nombre_role' => $nombreRole]);

        return User::factory()->create(['role_id' => $role->id]);
    }

    // borrarReportaje

    /**
     * Borrar un reportaje con ID inexistente devuelve errores.error.
     */
    public function test_fallo_id_no_existe(): void
    {
        $response = $this->withoutMiddleware()
            ->delete(route('borrarreportaje', 99999));

        $response->assertStatus(200);
        $response->assertViewIs('errores.error');
    }

    /**
     * Borrar carpeta cuando contiene un archivo de solo lectura (no se puede unlink)
     *  el manejador de errores de Laravel convierte el E_WARNING a ErrorException
     *  el catch lo recoge y devuelve errores.error.
     */
    public function test_fallo_borrar_carpeta_fisica(): void
    {
        $reportaje = Reportaje::factory()->create(['codigo' => 'COD_BORR1']);

        $rutaDir = storage_path('app/private/fotosreportajes/COD_BORR1');
        $rutaFoto = $rutaDir.'/foto.jpg';

        if (! is_dir($rutaDir)) {
            mkdir($rutaDir, 0777, true);
        }
        file_put_contents($rutaFoto, 'contenido');
        chmod($rutaFoto, 0444); // solo lectura  unlink falla en Windows

        $response = $this->withoutMiddleware()
            ->delete(route('borrarreportaje', $reportaje->id), [
                'accion_carpeta' => 'eliminar_carpeta',
            ]);

        // Limpieza (restaurar permisos primero)
        @chmod($rutaFoto, 0777);
        @unlink($rutaFoto);
        @rmdir($rutaDir);

        $response->assertStatus(200);
        $response->assertViewIs('errores.error');
    }

    // procesarFormEditarReportaje

    /**
     * email_usuario con formato inválido falla la validación.
     */
    public function test_fallo_email_invalido(): void
    {
        $reportaje = Reportaje::factory()->create();

        $response = $this->withoutMiddleware()
            ->put(route('editarreportaje', $reportaje->id), [
                'email_usuario' => 'no-es-un-email',
            ]);

        $response->assertSessionHasErrors(['email_usuario']);
    }

    /**
     * email_usuario con formato correcto pero inexistente en BD falla la validación.
     */
    public function test_fallo_email_no_existe(): void
    {
        $reportaje = Reportaje::factory()->create();

        $response = $this->withoutMiddleware()
            ->put(route('editarreportaje', $reportaje->id), [
                'email_usuario' => 'noexiste@ejemplo.com',
            ]);

        $response->assertSessionHasErrors(['email_usuario']);
    }

    /**
     * tipo fuera del enum TipoReportaje falla la validación.
     */
    public function test_fallo_tipo_invalido(): void
    {
        $reportaje = Reportaje::factory()->create();

        $response = $this->withoutMiddleware()
            ->put(route('editarreportaje', $reportaje->id), [
                'tipo' => 'tipo_inventado',
            ]);

        $response->assertSessionHasErrors(['tipo']);
    }

    /**
     * codigo con más de 20 caracteres falla la validación.
     */
    public function test_fallo_codigo_largo(): void
    {
        $reportaje = Reportaje::factory()->create();

        $response = $this->withoutMiddleware()
            ->put(route('editarreportaje', $reportaje->id), [
                'codigo' => str_repeat('X', 21),
            ]);

        $response->assertSessionHasErrors(['codigo']);
    }

    /**
     * descripcion con más de 250 caracteres falla la validación.
     */
    public function test_fallo_descripcion_larga(): void
    {
        $reportaje = Reportaje::factory()->create();

        $response = $this->withoutMiddleware()
            ->put(route('editarreportaje', $reportaje->id), [
                'descripcion' => str_repeat('a', 251),
            ]);

        $response->assertSessionHasErrors(['descripcion']);
    }

    /**
     * fecha_report con valor no-fecha falla la validación.
     */
    public function test_fallo_fecha_invalida(): void
    {
        $reportaje = Reportaje::factory()->create();

        $response = $this->withoutMiddleware()
            ->put(route('editarreportaje', $reportaje->id), [
                'fecha_report' => 'no-es-una-fecha',
            ]);

        $response->assertSessionHasErrors(['fecha_report']);
    }

    /**
     * Cuando el codigo nuevo ya existe como carpeta (no como código de BD),
     * el controlador devuelve errores.error directamente (sin llamar a rename).
     */
    public function test_fallo_carpeta_destino_existe(): void
    {
        $reportaje = Reportaje::factory()->create(['codigo' => 'COD_ORIG1']);

        // Crear la carpeta física DESTINO (simula que ya existe)
        $rutaDestino = storage_path('app/private/fotosreportajes/COD_DEST1');
        if (! is_dir($rutaDestino)) {
            mkdir($rutaDestino, 0777, true);
        }

        $response = $this->withoutMiddleware()
            ->put(route('editarreportaje', $reportaje->id), [
                'codigo' => 'COD_DEST1',
                'accion_carpeta' => 'renombrar',
            ]);

        // Limpieza
        @rmdir($rutaDestino);

        $response->assertStatus(200);
        $response->assertViewIs('errores.error');
    }

    // filtrarReportajes (no creo que haga falta probar más casos de filtrado)

    /**
     * Filtrar por email_usuario devuelve solo los reportajes de ese usuario.
     */
    public function test_filtrar_por_email_user(): void
    {
        $usuarioA = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);
        $usuarioB = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);

        Reportaje::factory()->count(2)->create(['user_id' => $usuarioA->id]);
        Reportaje::factory()->count(4)->create(['user_id' => $usuarioB->id]);

        $response = $this->withoutMiddleware()
            ->get(route('filtrarreportajes', ['email_usuario' => $usuarioA->email]));

        $response->assertStatus(200);
        $response->assertViewIs('parciales.listados.listarreportajes');
        $this->assertSame(2, $response->viewData('reportajes')->total());
    }

    /**
     * Filtrar por codigo devuelve solo los reportajes cuyo código contiene la cadena.
     */
    public function test_filtrar_por_codigo(): void
    {
        Reportaje::factory()->create(['codigo' => 'BUSCAR2001']);
        Reportaje::factory()->create(['codigo' => 'BUSCAR2002']);
        Reportaje::factory()->count(3)->create(); // códigos aleatorios del factory

        $response = $this->withoutMiddleware()
            ->get(route('filtrarreportajes', ['codigo' => 'BUSCAR200']));

        $response->assertStatus(200);
        $this->assertSame(2, $response->viewData('reportajes')->total());
    }

    /**
     * Filtrar por tipo devuelve solo los reportajes de ese tipo.
     */
    public function test_filtrar_por_tipo(): void
    {
        Reportaje::factory()->count(3)->create(['tipo' => TipoReportaje::BOOK->value]);
        Reportaje::factory()->count(2)->create(['tipo' => TipoReportaje::MODA->value]);

        $response = $this->withoutMiddleware()
            ->get(route('filtrarreportajes', ['tipo' => TipoReportaje::BOOK->value]));

        $response->assertStatus(200);
        $this->assertSame(3, $response->viewData('reportajes')->total());
    }

    // registrarNuevoReportaje

    /**
     * email_usuario con formato inválido falla la validación.
     */
    public function test_fallo_nuevo_email_invalido(): void
    {
        $response = $this->withoutMiddleware()
            ->post(route('nuevoreportaje'), [
                'tipo' => TipoReportaje::BOOK->value,
                'codigo' => 'REPOR0001',
                'fecha_report' => '2026-01-01',
                'email_usuario' => 'no-es-email',
            ]);

        $response->assertSessionHasErrors(['email_usuario']);
    }

    /**
     * email_usuario con formato correcto pero inexistente en BD falla la validación.
     */
    public function test_fallo_nuevo_email_no_existe(): void
    {
        $response = $this->withoutMiddleware()
            ->post(route('nuevoreportaje'), [
                'tipo' => TipoReportaje::BOOK->value,
                'codigo' => 'REPOR0002',
                'fecha_report' => '2026-01-01',
                'email_usuario' => 'noexiste@ejemplo.com',
            ]);

        $response->assertSessionHasErrors(['email_usuario']);
    }

    /**
     * tipo fuera del enum TipoReportaje falla la validación.
     */
    public function test_fallo_nuevo_tipo_invalido(): void
    {
        $usuario = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);

        $response = $this->withoutMiddleware()
            ->post(route('nuevoreportaje'), [
                'tipo' => 'tipo_inventado',
                'codigo' => 'REPOR0003',
                'fecha_report' => '2026-01-01',
                'email_usuario' => $usuario->email,
            ]);

        $response->assertSessionHasErrors(['tipo']);
    }

    /**
     * codigo con más de 20 caracteres falla la validación.
     */
    public function test_fallo_nuevo_codigo_largo(): void
    {
        $usuario = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);

        $response = $this->withoutMiddleware()
            ->post(route('nuevoreportaje'), [
                'tipo' => TipoReportaje::BOOK->value,
                'codigo' => str_repeat('X', 21),
                'fecha_report' => '2026-01-01',
                'email_usuario' => $usuario->email,
            ]);

        $response->assertSessionHasErrors(['codigo']);
    }

    /**
     * descripcion con más de 250 caracteres falla la validación.
     */
    public function test_fallo_nuevo_descripcion_larga(): void
    {
        $usuario = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);

        $response = $this->withoutMiddleware()
            ->post(route('nuevoreportaje'), [
                'tipo' => TipoReportaje::BOOK->value,
                'codigo' => 'REPOR0005',
                'descripcion' => str_repeat('d', 251),
                'fecha_report' => '2026-01-01',
                'email_usuario' => $usuario->email,
            ]);

        $response->assertSessionHasErrors(['descripcion']);
    }

    /**
     * fecha_report con valor no-fecha falla la validación.
     */
    public function test_fallo_nuevo_fecha_invalida(): void
    {
        $usuario = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);

        $response = $this->withoutMiddleware()
            ->post(route('nuevoreportaje'), [
                'tipo' => TipoReportaje::BOOK->value,
                'codigo' => 'REPOR0006',
                'fecha_report' => 'no-es-fecha',
                'email_usuario' => $usuario->email,
            ]);

        $response->assertSessionHasErrors(['fecha_report']);
    }

    /**
     * Cuando el código contiene un carácter inválido en nombres de directorio Windows (*),
     * mkdir falla  E_WARNING  ErrorException  catch  errores.error.
     */
    public function test_fallo_nuevo_error_crear_carpeta(): void
    {
        $usuario = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);

        // '*' es inválido en rutas de Windows  mkdir lanzará E_WARNING  ErrorException
        $codigoInvalido = 'REPOR*FAIL';

        $response = $this->withoutMiddleware()
            ->post(route('nuevoreportaje'), [
                'tipo' => TipoReportaje::BOOK->value,
                'codigo' => $codigoInvalido,
                'fecha_report' => '2026-01-01',
                'email_usuario' => $usuario->email,
                'accion_carpeta' => 'crear',
            ]);

        $response->assertStatus(200);
        $response->assertViewIs('errores.error');
    }

    // verificarFotos

    /**
     * Directorio con imágenes válidas  devuelve sus nombres.
     */
    public function test_obtiene_nombre_fotos(): void
    {
        $rutaDir = storage_path('app/private/fotosreportajes/VF_TEST1');
        if (! is_dir($rutaDir)) {
            mkdir($rutaDir, 0777, true);
        }
        file_put_contents($rutaDir.'/foto1.jpg', 'img');
        file_put_contents($rutaDir.'/foto2.png', 'img');
        file_put_contents($rutaDir.'/noesimagen.txt', 'txt'); // debe ignorarse

        $controlador = new ReportajeController;
        $resultado = $controlador->verificarFotos($rutaDir);

        // Limpieza
        @unlink($rutaDir.'/foto1.jpg');
        @unlink($rutaDir.'/foto2.png');
        @unlink($rutaDir.'/noesimagen.txt');
        @rmdir($rutaDir);

        $this->assertContains('foto1.jpg', $resultado);
        $this->assertContains('foto2.png', $resultado);
        $this->assertNotContains('noesimagen.txt', $resultado);
        $this->assertCount(2, $resultado);
    }

    /**
     * Los subdirectorios dentro de la carpeta no se incluyen en el resultado.
     * es necesario porque no se deben incluir los thumbnails que se guardan en subcarpetas.
     */
    public function test_verifica_fotos_sin_subdirs(): void
    {
        $rutaDir = storage_path('app/private/fotosreportajes/VF_TEST3');
        $rutaSubdir = $rutaDir.'/thumbs';
        if (! is_dir($rutaSubdir)) {
            mkdir($rutaSubdir, 0777, true);
        }
        file_put_contents($rutaDir.'/real.jpg', 'img');

        $controlador = new ReportajeController;
        $resultado = $controlador->verificarFotos($rutaDir);

        // Limpieza
        @unlink($rutaDir.'/real.jpg');
        @rmdir($rutaSubdir);
        @rmdir($rutaDir);

        $this->assertContains('real.jpg', $resultado);
        $this->assertNotContains('thumbs', $resultado);
        $this->assertCount(1, $resultado);
    }
}
