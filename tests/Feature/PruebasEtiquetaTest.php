<?php

namespace Tests\Feature;

use App\Models\Etiqueta;
use App\Models\Fotografia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PruebasEtiquetaTest extends TestCase
{
    use RefreshDatabase;

    // anadirEtiquetaFoto

    /**
     * La fotografía no existe devuelve vista de error.
     */
    public function testFalloFotoInexistente(): void
    {
        $response = $this->withoutMiddleware()
            ->put(route('anadiretiquetafoto', 99999), [
                'nombre_etiqueta' => 'PAISAJE',
            ]);

        $response->assertStatus(200);
        $response->assertViewIs('errores.error');
    }

    /**
     * nombre_etiqueta no existe aún en BD el controlador la crea y la asocia a la fotografía.
     */
    public function testAnadeEtiquetaNueva(): void
    {
        $fotografia = Fotografia::factory()->create();

        $response = $this->withoutMiddleware()
            ->put(route('anadiretiquetafoto', $fotografia->id), [
                'nombre_etiqueta' => 'etiqueta_nueva_xyz',
            ]);

        $response->assertStatus(200);
        $response->assertViewIs('errores.exito');

        $this->assertDatabaseHas('etiquetas', ['nombre_etiqueta' => 'ETIQUETA_NUEVA_XYZ']);

        $etiqueta = Etiqueta::where('nombre_etiqueta', 'ETIQUETA_NUEVA_XYZ')->first();
        $this->assertTrue(
            $fotografia->etiquetas()->where('etiqueta_id', $etiqueta->id)->exists()
        );
    }

    /**
     * nombre_etiqueta ya existe en BD y la fotografía aún no la tiene se asocia correctamente.
     */
    public function testAnadeEtiquetaExistente(): void
    {
        $fotografia = Fotografia::factory()->create();
        $etiqueta   = Etiqueta::factory()->create(['nombre_etiqueta' => 'RETRATO']);

        $response = $this->withoutMiddleware()
            ->put(route('anadiretiquetafoto', $fotografia->id), [
                'nombre_etiqueta' => 'RETRATO',
            ]);

        $response->assertStatus(200);
        $response->assertViewIs('errores.exito');

        $this->assertTrue(
            $fotografia->etiquetas()->where('etiqueta_id', $etiqueta->id)->exists()
        );
    }

    /**
     * La fotografía ya tiene esa etiqueta asociada devuelve vista de error.
     */
    public function testFalloEtiquetaYaAsociada(): void
    {
        $fotografia = Fotografia::factory()->create();
        $etiqueta   = Etiqueta::factory()->create(['nombre_etiqueta' => 'MODA']);

        // Asociar previamente la etiqueta a la fotografía
        $fotografia->etiquetas()->attach($etiqueta->id);

        $response = $this->withoutMiddleware()
            ->put(route('anadiretiquetafoto', $fotografia->id), [
                'nombre_etiqueta' => 'MODA',
            ]);

        $response->assertStatus(200);
        $response->assertViewIs('errores.error');
    }
}
