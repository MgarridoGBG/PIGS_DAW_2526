<?php

namespace Tests\Feature;

use App\Models\Soporte;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PruebasSoporteTest extends TestCase
{
    use RefreshDatabase;

        // borrarSoporte

    /**
     * Borrar un soporte con ID inexistente devuelve la vista errores.error.
     */
    public function testFalloIdNoExiste(): void
    {
        $respuesta = $this->withoutMiddleware()
            ->delete(route('borrarsoporte', 99999));

        $respuesta->assertStatus(200);
        $respuesta->assertViewIs('errores.error');
    }

        // procesarFormEditarSoporte

    /**
     * nombre_soport con más de 50 caracteres falla la validación.
     */
    public function testFalloNombreLargo(): void
    {
        $soporte = Soporte::factory()->create();

        $respuesta = $this->withoutMiddleware()
            ->put(route('editarsoporte', $soporte->id), [
                'nombre_soport' => str_repeat('a', 51),
            ]);

        $respuesta->assertSessionHasErrors(['nombre_soport']);
    }

    /**
     * disponibilidad con valor no booleano falla la validación.
     */
    public function testFalloDisponibilidadNoBool(): void
    {
        $soporte = Soporte::factory()->create();

        $respuesta = $this->withoutMiddleware()
            ->put(route('editarsoporte', $soporte->id), [
                'disponibilidad' => 'invalido',
            ]);

        $respuesta->assertSessionHasErrors(['disponibilidad']);
    }

    /**
     * precio negativo falla la validación.
     */
    public function testFalloPrecioNegativo(): void
    {
        $soporte = Soporte::factory()->create();

        $respuesta = $this->withoutMiddleware()
            ->put(route('editarsoporte', $soporte->id), [
                'precio' => -5.00,
            ]);

        $respuesta->assertSessionHasErrors(['precio']);
    }

    /**
     * precio no numérico falla la validación.
     */
    public function testFalloPrecioNoNumerico(): void
    {
        $soporte = Soporte::factory()->create();

        $respuesta = $this->withoutMiddleware()
            ->put(route('editarsoporte', $soporte->id), [
                'precio' => 'noesunnumero',
            ]);

        $respuesta->assertSessionHasErrors(['precio']);
    }

        // filtrarSoportes

    /**
     * Filtrar por nombre devuelve solo los soportes que coinciden.
     */
    public function testFiltrarPorNombre(): void
    {
        Soporte::factory()->create(['nombre_soport' => 'Canvas Premium']);
        Soporte::factory()->create(['nombre_soport' => 'Papel Mate']);
        Soporte::factory()->create(['nombre_soport' => 'Canvas Laminado']);

        $respuesta = $this->withoutMiddleware()
            ->get(route('filtrarsoportes', ['nombre_soport' => 'Canvas']));

        $respuesta->assertStatus(200);
        $respuesta->assertViewIs('parciales.listados.listarsoportes');

        $soportesFiltrados = $respuesta->viewData('soportes');
        $this->assertCount(2, $soportesFiltrados);
        foreach ($soportesFiltrados as $soporte) {
            $this->assertStringContainsStringIgnoringCase('Canvas', $soporte->nombre_soport);
        }
    }

    /**
     * Filtrar por precio mínimo devuelve solo los soportes con precio mayor o igual.
     */
    public function testFiltrarPorPrecioMinimo(): void
    {
        Soporte::factory()->create(['precio' => 10.00]);
        Soporte::factory()->create(['precio' => 50.00]);
        Soporte::factory()->create(['precio' => 100.00]);

        $respuesta = $this->withoutMiddleware()
            ->get(route('filtrarsoportes', ['precio_minimo' => 50]));

        $respuesta->assertStatus(200);
        $soportesFiltrados = $respuesta->viewData('soportes');
        $this->assertCount(2, $soportesFiltrados);
        foreach ($soportesFiltrados as $soporte) {
            $this->assertGreaterThanOrEqual(50, $soporte->precio);
        }
    }

    /**
     * Filtrar por precio máximo devuelve solo los soportes con precio menor o igual.
     */
    public function testFiltrarPorPrecioMaximo(): void
    {
        Soporte::factory()->create(['precio' => 10.00]);
        Soporte::factory()->create(['precio' => 50.00]);
        Soporte::factory()->create(['precio' => 100.00]);

        $respuesta = $this->withoutMiddleware()
            ->get(route('filtrarsoportes', ['precio_maximo' => 50]));

        $respuesta->assertStatus(200);
        $soportesFiltrados = $respuesta->viewData('soportes');
        $this->assertCount(2, $soportesFiltrados);
        foreach ($soportesFiltrados as $soporte) {
            $this->assertLessThanOrEqual(50, $soporte->precio);
        }
    }

        // registrarNuevoSoporte

    /**
     * nombre_soport con más de 50 caracteres falla la validación al crear.
     */
    public function testFalloNuevoNombreLargo(): void
    {
        $respuesta = $this->withoutMiddleware()
            ->post(route('nuevosoporte'), [
                'nombre_soport'  => str_repeat('a', 51),
                'disponibilidad' => 1,
                'precio'         => 25.00,
            ]);

        $respuesta->assertSessionHasErrors(['nombre_soport']);
    }

    /**
     * disponibilidad con valor no booleano falla la validación al crear.
     */
    public function testFalloNuevoDisponibilidadNoBool(): void
    {
        $respuesta = $this->withoutMiddleware()
            ->post(route('nuevosoporte'), [
                'nombre_soport'  => 'Soporte Válido',
                'disponibilidad' => 'invalido',
                'precio'         => 25.00,
            ]);

        $respuesta->assertSessionHasErrors(['disponibilidad']);
    }

    /**
     * precio negativo falla la validación al crear.
     */
    public function testFalloNuevoPrecioNegativo(): void
    {
        $respuesta = $this->withoutMiddleware()
            ->post(route('nuevosoporte'), [
                'nombre_soport'  => 'Soporte Válido',
                'disponibilidad' => 1,
                'precio'         => -10.00,
            ]);

        $respuesta->assertSessionHasErrors(['precio']);
    }

    /**
     * precio no numérico falla la validación al crear.
     */
    public function testFalloNuevoPrecioNoNumerico(): void
    {
        $respuesta = $this->withoutMiddleware()
            ->post(route('nuevosoporte'), [
                'nombre_soport'  => 'Soporte Válido',
                'disponibilidad' => 1,
                'precio'         => 'noesunnumero',
            ]);

        $respuesta->assertSessionHasErrors(['precio']);
    }
}
