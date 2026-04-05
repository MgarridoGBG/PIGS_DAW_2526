<?php

namespace Tests\Feature;

use App\Enums\EstadoCita;
use App\Enums\TurnoCita;
use App\Models\Cita;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PruebasCitaTest extends TestCase
{
    use RefreshDatabase;

    // procesarFormEditarCita — email_usuario

    /**
     * email_usuario con formato inválido (no es un email) error de validación.
     */
    public function test_fallo_email_invalido(): void
    {
        $cita = Cita::factory()->create();

        $response = $this->withoutMiddleware()
            ->put(route('editarcita', $cita->id), [
                'email_usuario' => 'esto-no-es-un-email',
            ]);

        $response->assertSessionHasErrors(['email_usuario']);
    }

    /**
     * email_usuario con formato correcto pero que no existe en la tabla users error de validación.
     */
    public function test_fallo_email_no_existe(): void
    {
        $cita = Cita::factory()->create();

        $response = $this->withoutMiddleware()
            ->put(route('editarcita', $cita->id), [
                'email_usuario' => 'noexiste@example.com',
            ]);

        $response->assertSessionHasErrors(['email_usuario']);
    }

    /**
     * email_usuario válido y existente se acepta la validación y se actualiza la cita.
     */
    public function test_actualiza_correcto_email(): void
    {
        $citaOriginal = Cita::factory()->create();
        // Creamos otro usuario (sin cita) que será el nuevo dueño
        $nuevoUsuario = User::factory()->create();

        $response = $this->withoutMiddleware()
            ->put(route('editarcita', $citaOriginal->id), [
                'email_usuario' => $nuevoUsuario->email,
            ]);

        $response->assertSessionDoesntHaveErrors(['email_usuario']);
        $response->assertViewIs('errores.exito');
        $this->assertDatabaseHas('citas', [
            'id' => $citaOriginal->id,
            'user_id' => $nuevoUsuario->id,
        ]);
    }

    // procesarFormEditarCita — estado_cita

    /**
     * estado_cita con valor fuera del enum EstadoCita error de validación.
     */
    public function test_fallo_estado_invalido(): void
    {
        $cita = Cita::factory()->create();

        $response = $this->withoutMiddleware()
            ->put(route('editarcita', $cita->id), [
                'estado_cita' => 'estado_inventado',
            ]);

        $response->assertSessionHasErrors(['estado_cita']);
    }

    /**
     * estado_cita valor válido del enum, pasa la validación.
     */
    public function test_estado_valido(): void
    {
        $cita = Cita::factory()->create();

        $response = $this->withoutMiddleware()
            ->put(route('editarcita', $cita->id), [
                'estado_cita' => EstadoCita::SOLICITADA->value,
            ]);

        $response->assertSessionDoesntHaveErrors(['estado_cita']);
        $this->assertDatabaseHas('citas', [
            'id' => $cita->id,
            'estado_cita' => EstadoCita::SOLICITADA->value,
        ]);
    }

    // procesarFormEditarCita — fecha_cita

    /**
     * fecha_cita con cadena que no es una fecha error de validación.
     */
    public function test_fallo_fecha_no_es_fecha(): void
    {
        $cita = Cita::factory()->create();

        $response = $this->withoutMiddleware()
            ->put(route('editarcita', $cita->id), [
                'fecha_cita' => 'no-es-una-fecha',
            ]);

        $response->assertSessionHasErrors(['fecha_cita']);
    }

    /**
     * fecha_cita con formato Y-m-d válido pasa la validación y actualiza la cita.
     */
    public function test_fecha_valida_actualiza(): void
    {
        $cita = Cita::factory()->create(['turno' => TurnoCita::MANANA->value]);
        $nuevaFecha = '2030-06-15';

        $response = $this->withoutMiddleware()
            ->put(route('editarcita', $cita->id), [
                'fecha_cita' => $nuevaFecha,
            ]);

        $response->assertSessionDoesntHaveErrors(['fecha_cita']);
        $this->assertDatabaseHas('citas', [
            'id' => $cita->id,
            'fecha_cita' => $nuevaFecha,
        ]);
    }
}
