<?php

namespace Tests\Feature;

use App\Enums\NombreRole;
use App\Models\Fotografia;
use App\Models\Reportaje;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PruebasFotografiaTest extends TestCase
{
    use RefreshDatabase;

    // Helper

    /** Crea un User cuyo Role tiene nombre_role = $nombre (string). */
    private function crearUsuarioConRole(string $nombre): User
    {
        $role = Role::factory()->create(['nombre_role' => $nombre]);

        return User::factory()->create(['role_id' => $role->id]);
    }

    // mostrarFotosReportaje

    /**
     * Un usuario que no es propietario del reportaje ni admin/empleado
     * y el reportaje es privado error de permisos.
     */
    public function test_fallo_user_sin_permisos(): void
    {
        $propietario = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);
        $reportaje = Reportaje::factory()->create(['user_id' => $propietario->id, 'publico' => false]);

        $otroUsuario = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);

        $response = $this->actingAs($otroUsuario)
            ->withoutMiddleware()
            ->get(route('reportajefotos', $reportaje->id));

        $response->assertStatus(200);
        $response->assertViewIs('errores.error');
    }

    /**
     * El propietario del reportaje privado puede ver su galería.
     */
    public function test_user_propietario_accede_rep(): void
    {
        $propietario = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);
        $reportaje = Reportaje::factory()->create(['user_id' => $propietario->id, 'publico' => false]);

        $response = $this->actingAs($propietario)
            ->withoutMiddleware()
            ->get(route('reportajefotos', $reportaje->id));

        $response->assertStatus(200);
        $response->assertViewIs('zonaprivada.galeriareportajeprivado');
    }

    /**
     * Un administrador o empleado puede ver el reportaje privado de cualquier usuario.
     */
    public function test_admin_accede_rep_privado(): void
    {
        $propietario = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);
        $reportaje = Reportaje::factory()->create(['user_id' => $propietario->id, 'publico' => false]);

        $admin = $this->crearUsuarioConRole(NombreRole::ADMIN->value);

        $response = $this->actingAs($admin)
            ->withoutMiddleware()
            ->get(route('reportajefotos', $reportaje->id));

        $response->assertStatus(200);
        $response->assertViewIs('zonaprivada.galeriareportajeprivado');
    }

    // mostrarFoto

    /**
     * El propietario de la foto puede verla (vista privada).
     */
    public function test_user_prop_accede_foto(): void
    {
        $propietario = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);
        $reportaje = Reportaje::factory()->create(['user_id' => $propietario->id, 'publico' => false]);
        $foto = Fotografia::factory()->create(['reportaje_id' => $reportaje->id]);

        $response = $this->actingAs($propietario)
            ->withoutMiddleware()
            ->get(route('mostrarfoto', $foto->id));

        $response->assertStatus(200);
        $response->assertViewIs('zonaprivada.mostrarfoto');
    }

    /**
     * Un empleado o admin puede ver cualquier foto privada.
     */
    public function test_empleado_accede_foto_privada(): void
    {
        $propietario = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);
        $reportaje = Reportaje::factory()->create(['user_id' => $propietario->id, 'publico' => false]);
        $foto = Fotografia::factory()->create(['reportaje_id' => $reportaje->id]);

        $empleado = $this->crearUsuarioConRole(NombreRole::EMPLEADO->value);

        $response = $this->actingAs($empleado)
            ->withoutMiddleware()
            ->get(route('mostrarfoto', $foto->id));

        $response->assertStatus(200);
        $response->assertViewIs('zonaprivada.mostrarfoto');
    }

    /**
     * Un usuario ajeno al reportaje privado no puede ver la foto.
     */
    public function test_fallo_user_sin_permiso_accede_foto(): void
    {
        $propietario = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);
        $reportaje = Reportaje::factory()->create(['user_id' => $propietario->id, 'publico' => false]);
        $foto = Fotografia::factory()->create(['reportaje_id' => $reportaje->id]);

        $otroUsuario = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);

        $response = $this->actingAs($otroUsuario)
            ->withoutMiddleware()
            ->get(route('mostrarfoto', $foto->id));

        $response->assertStatus(200);
        $response->assertViewIs('errores.error');
    }

    // servirFotoStorage

    /**
     * La ruta pedida no existe en storage errores.error.
     */
    public function test_fallo_no_foto_fisica(): void
    {
        Storage::fake('local');

        $response = $this->withoutMiddleware()
            ->get('/private/fotosreportajes/REPOR_X/noexiste.jpg');

        $response->assertStatus(200);
        $response->assertViewIs('errores.error');
    }

    /**
     * Archivo y BD correctos pero usuario sin permiso errores.error.
     */
    public function test_fallo_foto_storage_user_sin_permiso(): void
    {
        Storage::fake('local');

        $propietario = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);
        $reportaje = Reportaje::factory()->create(['user_id' => $propietario->id, 'publico' => false, 'codigo' => 'REPOR0001']);
        $foto = Fotografia::factory()->create(['reportaje_id' => $reportaje->id, 'nombre_foto' => 'prueba.jpg']);

        Storage::disk('local')->put('fotosreportajes/REPOR0001/prueba.jpg', 'fake-content');

        $otroUsuario = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);

        $response = $this->actingAs($otroUsuario)
            ->withoutMiddleware()
            ->get('/private/fotosreportajes/REPOR0001/prueba.jpg');

        $response->assertStatus(200);
        $response->assertViewIs('errores.error');
    }

    // filtrarFotografias

    /**
     * Filtrar por reportaje_codigo devuelve solo las fotos de ese reportaje.
     * el resto funcionan igual pero con diferentes campos.
     */
    public function test_filtrar_foto_codigo_rep(): void
    {
        $reportajeBuscado = Reportaje::factory()->create(['codigo' => 'BUSCAR001']);
        $reportajeOtro = Reportaje::factory()->create(['codigo' => 'OTRO00001']);

        Fotografia::factory()->count(3)->create(['reportaje_id' => $reportajeBuscado->id]);
        Fotografia::factory()->count(2)->create(['reportaje_id' => $reportajeOtro->id]);

        $response = $this->withoutMiddleware()
            ->get(route('filtrarfotografias', ['reportaje_codigo' => 'BUSCAR001']));

        $response->assertStatus(200);
        $response->assertViewIs('parciales.listados.listarfotografias');

        $fotografias = $response->viewData('fotografias');
        $this->assertSame(3, $fotografias->total());
    }

    // registrarNuevaFotografia

    /**
     * nombre_foto con más de 100 caracteres error de validación.
     */
    public function test_fallo_nombre_foto_largo(): void
    {
        $response = $this->withoutMiddleware()
            ->post(route('nuevafotografia'), [
                'nombre_foto' => str_repeat('a', 97).'.jpg',
                'reportaje_codigo' => 'REPOR0001',
            ]);

        $response->assertSessionHasErrors(['nombre_foto']);
    }

    /**
     * reportaje_codigo con más de 20 caracteres error de validación.
     */
    public function test_fallo_codigo_reportaje_largo(): void
    {
        $response = $this->withoutMiddleware()
            ->post(route('nuevafotografia'), [
                'nombre_foto' => 'foto.jpg',
                'reportaje_codigo' => 'XXXXXXXXXXXXXXXXXXXXX',
            ]);

        $response->assertSessionHasErrors(['reportaje_codigo']);
    }

    /**
     * reportaje_codigo inexistente en BD error de validación (regla exists).
     */
    public function test_fallo_codigo_reportaje_no_existe(): void
    {
        $response = $this->withoutMiddleware()
            ->post(route('nuevafotografia'), [
                'nombre_foto' => 'foto.jpg',
                'reportaje_codigo' => 'NOEXISTE001',
            ]);

        $response->assertSessionHasErrors(['reportaje_codigo']);
    }

    // borrarFotografia

    /**
     * Cuando el archivo físico existe pero no puede eliminarse (se simula con
     * un directorio en lugar del archivo: unlink() sobre un directorio falla),
     * el controlador debe devolver errores.error.
     */
    public function test_fallo_borrar_archivo_fisico(): void
    {
        $reportaje = Reportaje::factory()->create(['codigo' => 'REPOR_DEL1']);
        $fotografia = Fotografia::factory()->create([
            'reportaje_id' => $reportaje->id,
            'nombre_foto' => 'foto_borrar.jpg',
        ]);

        // Crear un directorio en lugar del archivo para que unlink() falle
        $rutaDir = storage_path('app/private/fotosreportajes/REPOR_DEL1');
        $rutaFalso = $rutaDir.'/foto_borrar.jpg';

        if (! is_dir($rutaDir)) {
            mkdir($rutaDir, 0777, true);
        }
        // Crear un subdirectorio con el nombre del "archivo" file_exists = true, unlink = false
        if (! is_dir($rutaFalso)) {
            mkdir($rutaFalso, 0777, true);
        }

        $response = $this->withoutMiddleware()
            ->delete(route('borrarfotografia', $fotografia->id), [
                'accion_archivo' => 'borrar_archivo',
            ]);

        // Limpieza del subdirectorio temporal
        @rmdir($rutaFalso);
        @rmdir($rutaDir);

        $response->assertStatus(200);
        $response->assertViewIs('errores.error');
    }

    // procesarFormEditarFotografia

    /**
     * nombre_foto con más de 100 caracteres error de validación.
     */
    public function test_fallo_editar_nombre_foto_largo(): void
    {
        $reportaje = Reportaje::factory()->create();
        $fotografia = Fotografia::factory()->create(['reportaje_id' => $reportaje->id]);

        $response = $this->withoutMiddleware()
            ->put(route('editarfotografia', $fotografia->id), [
                'nombre_foto' => str_repeat('a', 101),
            ]);

        $response->assertSessionHasErrors(['nombre_foto']);
    }

    /**
     * El archivo con el nuevo nombre ya existe físicamente (no se puede renombrar)
     * el controlador devuelve errores.error.
     */
    public function test_fallo_editar_foto_archivo_existe(): void
    {
        $reportaje = Reportaje::factory()->create(['codigo' => 'REPOR_EDT1']);
        $fotografia = Fotografia::factory()->create([
            'reportaje_id' => $reportaje->id,
            'nombre_foto' => 'foto_vieja.jpg',
        ]);

        // Crear los dos archivos físicos: el original y el destino
        $rutaDir = storage_path('app/private/fotosreportajes/REPOR_EDT1');
        if (! is_dir($rutaDir)) {
            mkdir($rutaDir, 0777, true);
        }
        file_put_contents($rutaDir.'/foto_vieja.jpg', 'contenido original');
        file_put_contents($rutaDir.'/foto_nueva.jpg', 'contenido destino');

        $response = $this->withoutMiddleware()
            ->put(route('editarfotografia', $fotografia->id), [
                'nombre_foto' => 'foto_nueva.jpg',
                'accion_archivo' => 'renombrar',
            ]);

        // Limpieza
        @unlink($rutaDir.'/foto_vieja.jpg');
        @unlink($rutaDir.'/foto_nueva.jpg');
        @rmdir($rutaDir);

        $response->assertStatus(200);
        $response->assertViewIs('errores.error');
    }
}
