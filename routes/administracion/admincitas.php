<?php

use App\Http\Controllers\Api\CitasCalendarController;
use App\Http\Controllers\CitaController;
use Illuminate\Support\Facades\Route;

// GESTION DE CITAS

// Rutas para listar, editar y borrar citas solo accesible para roles con privilegio 'admin_basico'
Route::get('/listarcitas', [CitaController::class, 'listarCitas'])
    ->name('listarcitas')->middleware('chequeaprivilegio:admin_basico');

Route::get('/formeditarcita/{id}', [CitaController::class, 'mostrarFormEditarCita'])
    ->name('formeditarcita')->middleware('chequeaprivilegio:admin_basico');

Route::put('/editarcita/{id}', [CitaController::class, 'procesarFormEditarCita'])
    ->name('editarcita')->middleware('chequeaprivilegio:admin_basico');

Route::delete('/borrarcita/{id}', [CitaController::class, 'borrarCita'])
    ->name('borrarcita')->middleware('chequeaprivilegio:admin_basico');

Route::delete('/borrarcitapropia', [CitaController::class, 'borrarCitaPropia'])
    ->name('borrarcitapropia')->middleware('chequeaprivilegio:concertar_cita'); // permite a un usuario borrar su propia cita, pero no la de otros

Route::match(['get', 'post'], '/filtrarcitas', [CitaController::class, 'filtrarCitas'])
    ->name('filtrarcitas')->middleware('chequeaprivilegio:admin_basico');

// Ruta para mostrar el calendario de citas (solo usuarios autenticados)
Route::view('/calendario', 'zonapublica.calendario')->middleware('chequeaprivilegio:concertar_cita')->name('calendario'); // exige login

// Después de mucho intentarlo, acabo poniendo las rutas API para manejar las citas del calendario aqui, Para poder usar la autenticación web.
Route::middleware('auth')->group(function () {
    Route::get('/api/citas', [CitasCalendarController::class, 'index']);
    Route::put('/api/micita', [CitasCalendarController::class, 'actualizarMiCita'])->middleware('chequeaprivilegio:concertar_cita');
    Route::delete('/api/micita', [CitasCalendarController::class, 'cancelarMiCita'])->middleware('chequeaprivilegio:concertar_cita');
});
