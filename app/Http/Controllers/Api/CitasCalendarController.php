<?php

namespace App\Http\Controllers\Api;

use App\Enums\EstadoCita;
use App\Enums\TurnoCita;
use App\Http\Controllers\Controller;
use App\Models\Cita;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * API controller para gestionar citas en formato compatible con FullCalendar.
 * Creado según la documentacion de la librería.
 * Proporciona endpoints para obtener citas, crearo reprogramar la cita del usuario autenticado y cancelar la cita del usuario autenticado.
 * La documantacion de FullCalendar esta en https://fullcalendar.io/docs.
 */
class CitasCalendarController extends Controller
{
    /**
     * Devuelve las citas (ocupación) en el rango solicitado por FullCalendar. ('start' y 'end')
     * Valida el rango y retorna un array de citas.
     *
     * @return \Illuminate\Support\Collection Colección de citas formateadas para FullCalendar
     */

    // Devuelve ocupación (2 líneas por día) sin datos personales
    public function index(Request $peticion)
    {
        $datosCalendar = $peticion->validate([
            'start' => ['required', 'date'], // FullCalendar envía 'start' y 'end' como fechas ISO (ej: 2024-09-01)
            'end' => ['required', 'date', 'after_or_equal:start'], // Validamos que 'end' sea igual o posterior a 'start'
        ]);

        $citas = Cita::query()
            ->whereDate('fecha_cita', '>=', $datosCalendar['start']) // start inclusivo que es lo que FullCalendar envía
            ->whereDate('fecha_cita', '<', $datosCalendar['end']) // end exclusivo
            ->orderBy('fecha_cita')
            ->orderBy('turno')
            ->get(['id', 'fecha_cita', 'turno']);

        return $citas->map(fn ($cita) => [ // Formato necesario para FullCalendar
            'id' => $cita->id,
            'start' => $cita->fecha_cita->format('Y-m-d'), // FullCalendar espera fecha ISO (puedes incluir hora si no es allDay)
            'allDay' => true, // Si no quieres que se muestre como evento de día completo, ajusta esto y el formato de 'start'
            'title' => $cita->turno === TurnoCita::MANANA->value ? 'Mañana (ocupado)' : 'Tarde (ocupado)',
            'extendedProps' => [
                'turno' => $cita->turno, // Puedes incluir más propiedades si las necesitas en el frontend
            ],
        ]);
    }

    /**
     * Crea o reprograma la cita del usuario autenticado.
     *
     * Método: PUT /api/micita
     *
     * Retorna JSON con formato de cita para FullCalendar.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function actualizarMiCita(Request $peticion)
    {
        $user = $peticion->user();

        $datosCalendar = $peticion->validate([
            'fecha' => ['required', 'date'],
            'turno' => ['required', 'in:'.implode(',', TurnoCita::values())],
        ]);

        // Si el turno está ocupado por OTRO, no se puede
        $taken = Cita::query()
            ->where('fecha_cita', $datosCalendar['fecha'])
            ->where('turno', $datosCalendar['turno'])
            ->where('user_id', '!=', $user->id)
            ->exists();

        if ($taken) {
            throw ValidationException::withMessages([
                'turno' => 'Ese turno ya está reservado.',
            ]);
        }

        // Upsert por user_id (1 cita por usuario)
        // Además, marcamos estado activo
        try {
            $cita = Cita::updateOrCreate(
                ['user_id' => $user->id],
                ['fecha_cita' => $datosCalendar['fecha'], 'turno' => $datosCalendar['turno'], 'estado_cita' => EstadoCita::SOLICITADA->value]
            );
        } catch (QueryException $e) { // Por si dos users intentan reservar el mismo turno al mismo tiempo.
            throw ValidationException::withMessages([
                'turno' => 'Ese turno acaba de reservarse. Prueba otro.',
            ]);
        }

        return response()->json([
            'id' => $cita->id,
            'start' => $cita->fecha_cita->format('Y-m-d'),
            'allDay' => true,
            'title' => $cita->turno === TurnoCita::MANANA->value ? 'Mañana (ocupado)' : 'Tarde (ocupado)',
            'extendedProps' => ['turno' => $cita->turno],
        ]);
    }

    /**
     * Cancela (elimina) la cita del usuario autenticado.
     *
     * Método: DELETE /api/micita
     *
     * @return \Illuminate\Http\Response (204 No Content)
     */
    public function cancelarMiCita(Request $peticion)
    {
        $cita = $peticion->user()->cita;

        if (! $cita) {
            return response()->noContent();
        }

        $cita->delete();

        return response()->noContent();
    }
}
