<?php

namespace Tests\Feature;

use App\Enums\EstadoCita;
use App\Enums\NombreRole;
use App\Enums\TurnoCita;
use App\Models\Cita;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PruebaCitasCalendarTest extends TestCase
{
    use RefreshDatabase;

    
    // Helper

    // Crea un User con el Role indicado.
    private function crearUsuarioConRole(string $nombre): User
    {
        $role = Role::factory()->create(['nombre_role' => $nombre]);
        return User::factory()->create(['role_id' => $role->id]);
    }

      // index  GET /api/citas
    // Sin autenticación redirige al login. 
    public function testIndexRequiereAutenticacion(): void
    {
        $response = $this->getJson('/api/citas');

        $response->assertStatus(401);
    }

    // Devuelve las citas del rango con el formato esperado por FullCalendar.
    public function testIndexDevuelveCitasEnRango(): void
    {
        $user = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);

        Cita::factory()->create([
            'user_id'    => $user->id,
            'fecha_cita' => '2026-06-05',
            'turno'      => TurnoCita::MANANA->value,
        ]);

        $response = $this->actingAs($user)
            ->withoutMiddleware()
            ->getJson('/api/citas?start=2026-06-01&end=2026-06-30');

        $response->assertOk();
        $response->assertJsonCount(1);
        $response->assertJsonFragment([
            'start'  => '2026-06-05',
            'allDay' => true,
            'title'  => 'Mañana (ocupado)',
        ]);
        $response->assertJsonFragment([
            'turno' => TurnoCita::MANANA->value,
        ]);
    }


      // actualizarMiCita  PUT /api/micita

    // 'fecha' y 'turno' son obligatorios.
    public function testActualizarCitaFalloSinDatos(): void
    {
        $user = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);

        $response = $this->actingAs($user)
            ->withoutMiddleware()
            ->putJson('/api/micita', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['fecha', 'turno']);
    }

    // Turno no permitido falla la validación.
    public function testActualizarCitaFalloTurnoInvalido(): void
    {
        $user = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);

        $response = $this->actingAs($user)
            ->withoutMiddleware()
            ->putJson('/api/micita', [
                'fecha' => '2026-07-10',
                'turno' => 'noche',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['turno']);
    }

    // Turno ya está reservado se devuelve error de validación.
    public function testActualizarCitaFalloTurnoOcupado(): void
    {
        $otroUser = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);
        Cita::factory()->create([
            'user_id'    => $otroUser->id,
            'fecha_cita' => '2026-07-10',
            'turno'      => TurnoCita::MANANA->value,
        ]);

        $user = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);

        $response = $this->actingAs($user)
            ->withoutMiddleware()
            ->putJson('/api/micita', [
                'fecha' => '2026-07-10',
                'turno' => TurnoCita::MANANA->value,
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['turno']);
    }

    // Un usuario sin cita previa crea una nueva con éxito.
    public function testActualizarCitaCreaCorrectamente(): void
    {
        $user = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);

        $response = $this->actingAs($user)
            ->withoutMiddleware()
            ->putJson('/api/micita', [
                'fecha' => '2026-07-15',
                'turno' => TurnoCita::TARDE->value,
            ]);

        $response->assertOk();
        $response->assertJsonFragment([
            'start'  => '2026-07-15',
            'allDay' => true,
            'title'  => 'Tarde (ocupado)',
        ]);
        $response->assertJsonFragment(['turno' => TurnoCita::TARDE->value]);

        $this->assertDatabaseHas('citas', [
            'user_id'     => $user->id,
            'fecha_cita'  => '2026-07-15',
            'turno'       => TurnoCita::TARDE->value,
            'estado_cita' => EstadoCita::SOLICITADA->value,
        ]);
    }

    // Un usuario con cita previa la reprograma con éxito.
    public function testActualizarCitaReprogramaCitaExistente(): void
    {
        $user = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);
        Cita::factory()->create([
            'user_id'     => $user->id,
            'fecha_cita'  => '2026-07-01',
            'turno'       => TurnoCita::MANANA->value,
            'estado_cita' => EstadoCita::CONFIRMADA->value,
        ]);

        $response = $this->actingAs($user)
            ->withoutMiddleware()
            ->putJson('/api/micita', [
                'fecha' => '2026-08-20',
                'turno' => TurnoCita::TARDE->value,
            ]);

        $response->assertOk();
        $response->assertJsonFragment(['start' => '2026-08-20']);

        // Solo debe existir una cita para este usuario
        $this->assertSame(1, Cita::where('user_id', $user->id)->count());
        $this->assertDatabaseHas('citas', [
            'user_id'     => $user->id,
            'fecha_cita'  => '2026-08-20',
            'turno'       => TurnoCita::TARDE->value,
            'estado_cita' => EstadoCita::SOLICITADA->value,
        ]);
    }

      // cancelarMiCita  DELETE /api/micita

    // Si el usuario no tiene cita devuelve 204 sin error.
    public function testCancelarCitaSinCitaDevuelve204(): void
    {
        $user = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);

        $response = $this->actingAs($user)
            ->withoutMiddleware()
            ->deleteJson('/api/micita');

        $response->assertNoContent();
    }

    // Con cita existente la elimina y devuelve 204.
    public function testCancelarCitaEliminaCitaYDevuelve204(): void
    {
        $user = $this->crearUsuarioConRole(NombreRole::CLIENTE->value);
        $cita = Cita::factory()->create([
            'user_id'    => $user->id,
            'fecha_cita' => '2026-09-05',
            'turno'      => TurnoCita::MANANA->value,
        ]);

        $response = $this->actingAs($user)
            ->withoutMiddleware()
            ->deleteJson('/api/micita');

        $response->assertNoContent();
        $this->assertDatabaseMissing('citas', ['id' => $cita->id]);
    }
}
