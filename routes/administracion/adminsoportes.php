<?php

use App\Http\Controllers\SoporteController;
use Illuminate\Support\Facades\Route;

// GESTION DE SOPORTES
// Rutas para listar, editar, crear y borrar soportes, solo accesible para roles con privilegio 'admin_avanzado'
Route::get('/listarsoportes', [SoporteController::class, 'listarSoportes'])
    ->name('listarsoportes')->middleware('chequeaprivilegio:admin_avanzado');

Route::get('/formeditarsoporte/{id}', [SoporteController::class, 'mostrarFormEditarSoporte'])
    ->name('formeditarsoporte')->middleware('chequeaprivilegio:admin_avanzado');

Route::put('/editarsoporte/{id}', [SoporteController::class, 'procesarFormEditarSoporte'])
    ->name('editarsoporte')->middleware('chequeaprivilegio:admin_avanzado');

Route::delete('/borrarsoporte/{id}', [SoporteController::class, 'borrarSoporte'])
    ->name('borrarsoporte')->middleware('chequeaprivilegio:admin_avanzado');

Route::match(['get', 'post'], '/filtrarsoportes', [SoporteController::class, 'filtrarSoportes'])
    ->name('filtrarsoportes')->middleware('chequeaprivilegio:admin_avanzado');

Route::get('/nuevosoporte', [SoporteController::class, 'mostrarFormNuevoSoporte'])
    ->name('formnuevosoporte')->middleware('chequeaprivilegio:admin_avanzado');

Route::post('/nuevosoporte', [SoporteController::class, 'registrarNuevoSoporte'])
    ->name('nuevosoporte')->middleware('chequeaprivilegio:admin_avanzado');
