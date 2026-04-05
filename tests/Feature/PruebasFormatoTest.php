<?php

namespace Tests\Feature;

use App\Models\Formato;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PruebasFormatoTest extends TestCase
{
    use RefreshDatabase;

    // Funcion filtrarFormatos. Solo se prueba el filtrado por nombre, ya que es
    // el resto funcionan igual pero con diferentes campos.

    /**
     * Al filtrar (por nombre) devuelve la vista correcta con los resultados.
     */
    public function test_filtrar_formatos(): void
    {
        Formato::factory()->create(['nombre_format' => 'Canvas Grande']);
        Formato::factory()->create(['nombre_format' => 'Pequeño']);

        $response = $this->withoutMiddleware()
            ->post(route('filtrarformatos'), ['nombre' => 'Canvas Grande']);

        $response->assertStatus(200);
        $response->assertViewIs('parciales.listados.listaformatos');
        $this->assertEquals(1, $response->viewData('formatos')->total());
    }

    // procesarFormEditarFormato

    /**
     * Editar con ancho negativo debe fallar la validación.
     */
    public function test_fallo_ancho_negativo(): void
    {
        $formato = Formato::factory()->create();

        $response = $this->withoutMiddleware()
            ->put(route('editarformato', $formato->id), ['ancho' => -1]);

        $response->assertSessionHasErrors(['ancho']);
    }

    /**
     * Editar con alto negativo debe fallar la validación.
     */
    public function test_fallo_alto_negativo(): void
    {
        $formato = Formato::factory()->create();

        $response = $this->withoutMiddleware()
            ->put(route('editarformato', $formato->id), ['alto' => -1]);

        $response->assertSessionHasErrors(['alto']);
    }

    /**
     * Editar con nombre_format de más de 50 caracteres debe fallar la validación.
     */
    public function test_fallo_nombre_largo(): void
    {
        $formato = Formato::factory()->create();

        $response = $this->withoutMiddleware()
            ->put(route('editarformato', $formato->id), [
                'nombre_format' => str_repeat('a', 51),
            ]);

        $response->assertSessionHasErrors(['nombre_format']);
    }

    // registrarNuevoFormato

    /**
     * Crear formato con ancho negativo debe fallar la validación.
     */
    public function test_fallo_nuevo_ancho_negativo(): void
    {
        $response = $this->withoutMiddleware()
            ->post(route('nuevoformato'), [
                'nombre_format' => 'Test',
                'ancho' => -10,
                'alto' => 20,
            ]);

        $response->assertSessionHasErrors(['ancho']);
    }

    /**
     * Crear formato con alto negativo debe fallar la validación.
     */
    public function test_fallo_nuevo_alto_negativo(): void
    {
        $response = $this->withoutMiddleware()
            ->post(route('nuevoformato'), [
                'nombre_format' => 'Test',
                'ancho' => 10,
                'alto' => -5,
            ]);

        $response->assertSessionHasErrors(['alto']);
    }

    /**
     * Crear formato con nombre_format de más de 50 caracteres debe fallar la validación.
     */
    public function test_fallo_nuevo_nombre_largo(): void
    {
        $response = $this->withoutMiddleware()
            ->post(route('nuevoformato'), [
                'nombre_format' => str_repeat('x', 51),
                'ancho' => 10,
                'alto' => 20,
            ]);

        $response->assertSessionHasErrors(['nombre_format']);
    }

    /**
     * Crear formato sin nombre_format es obligatorio.
     */
    public function test_fallo_nuevo_nombre_obligatorio(): void
    {
        $response = $this->withoutMiddleware()
            ->post(route('nuevoformato'), [
                'ancho' => 10,
                'alto' => 20,
            ]);

        $response->assertSessionHasErrors(['nombre_format']);
    }

    /**
     * Crear formato sin ancho es obligatorio.
     */
    public function test_fallo_nuevo_ancho_obligatorio(): void
    {
        $response = $this->withoutMiddleware() //
            ->post(route('nuevoformato'), [
                'nombre_format' => 'Test',
                'alto' => 20,
            ]);

        $response->assertSessionHasErrors(['ancho']);
    }

    /**
     * Crear formato sin alto es obligatorio.
     */
    public function test_fallo_nuevo_alto_obligatorio(): void
    {
        $response = $this->withoutMiddleware()
            ->post(route('nuevoformato'), [
                'nombre_format' => 'Test',
                'ancho' => 10,
            ]);

        $response->assertSessionHasErrors(['alto']);
    }
}
