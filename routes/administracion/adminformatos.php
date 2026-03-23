<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\formatoController;

// GESTION DE FORMATOS DE IMAGEN
// Rutas para listar, editar, crear y borrar formatos de imagen, solo accesible para roles con privilegio 'admin_avanzado'
Route::get('/listarformatos', [formatoController::class, 'listarFormatos'])
    ->name('listarformatos')->middleware('chequeaprivilegio:admin_avanzado');

Route::get('/formeditarformato/{id}', [formatoController::class, 'mostrarFormEditarFormato'])
    ->name('formeditarformato')->middleware('chequeaprivilegio:admin_avanzado');

Route::put('/editarformato/{id}', [formatoController::class, 'procesarFormEditarFormato'])
    ->name('editarformato')->middleware('chequeaprivilegio:admin_avanzado');

Route::delete('/borrarformato/{id}', [formatoController::class, 'borrarFormato'])
    ->name('borrarformato')->middleware('chequeaprivilegio:admin_avanzado');

Route::match(['get', 'post'], '/filtrarformatos', [formatoController::class, 'filtrarFormatos'])
    ->name('filtrarformatos')->middleware('chequeaprivilegio:admin_avanzado');

Route::get('/nuevoformato', [formatoController::class, 'mostrarFormNuevoFormato'])
    ->name('formnuevoformato')->middleware('chequeaprivilegio:admin_avanzado');

Route::post('/nuevoformato', [formatoController::class, 'registrarNuevoFormato'])
    ->name('nuevoformato')->middleware('chequeaprivilegio:admin_avanzado');