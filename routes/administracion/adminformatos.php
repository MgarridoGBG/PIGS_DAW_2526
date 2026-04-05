<?php

use App\Http\Controllers\FormatoController;
use Illuminate\Support\Facades\Route;

// GESTION DE FORMATOS DE IMAGEN
// Rutas para listar, editar, crear y borrar formatos de imagen, solo accesible para roles con privilegio 'admin_avanzado'
Route::get('/listarformatos', [FormatoController::class, 'listarFormatos'])
    ->name('listarformatos')->middleware('chequeaprivilegio:admin_avanzado');

Route::get('/formeditarformato/{id}', [FormatoController::class, 'mostrarFormEditarFormato'])
    ->name('formeditarformato')->middleware('chequeaprivilegio:admin_avanzado');

Route::put('/editarformato/{id}', [FormatoController::class, 'procesarFormEditarFormato'])
    ->name('editarformato')->middleware('chequeaprivilegio:admin_avanzado');

Route::delete('/borrarformato/{id}', [FormatoController::class, 'borrarFormato'])
    ->name('borrarformato')->middleware('chequeaprivilegio:admin_avanzado');

Route::match(['get', 'post'], '/filtrarformatos', [FormatoController::class, 'filtrarFormatos'])
    ->name('filtrarformatos')->middleware('chequeaprivilegio:admin_avanzado');

Route::get('/nuevoformato', [FormatoController::class, 'mostrarFormNuevoFormato'])
    ->name('formnuevoformato')->middleware('chequeaprivilegio:admin_avanzado');

Route::post('/nuevoformato', [FormatoController::class, 'registrarNuevoFormato'])
    ->name('nuevoformato')->middleware('chequeaprivilegio:admin_avanzado');
