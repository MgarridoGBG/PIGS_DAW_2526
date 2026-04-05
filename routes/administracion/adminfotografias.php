<?php

use App\Http\Controllers\FotografiaController;
use Illuminate\Support\Facades\Route;

// GESTION DE FOTOGRAFÍAS
// Ruta para listar fotografías, solo accesible para roles con privilegio 'admin_basico'
Route::get('/listarfotografias', [FotografiaController::class, 'listarFotografias'])
    ->name('listarfotografias')->middleware('chequeaprivilegio:admin_basico');

// Ruta para filtrar fotografías (GET o POST)
Route::match(['get', 'post'], '/filtrarfotografias', [FotografiaController::class, 'filtrarFotografias'])
    ->name('filtrarfotografias')->middleware('chequeaprivilegio:admin_basico');

// Formulario para crear nueva fotografía
Route::get('/formnuevafotografia', [FotografiaController::class, 'mostrarFormNuevaFotografia'])
    ->name('formnuevafotografia')->middleware('chequeaprivilegio:admin_basico');

// Procesar creación de nueva fotografía
Route::post('/nuevafotografia', [FotografiaController::class, 'registrarNuevaFotografia'])
    ->name('nuevafotografia')->middleware('chequeaprivilegio:admin_basico');

// Borrar fotografía
Route::delete('/borrarfotografia/{id}', [FotografiaController::class, 'borrarFotografia'])
    ->name('borrarfotografia')->middleware('chequeaprivilegio:admin_basico');

// Editar (renombrar) fotografia
Route::put('/editarfotografia/{id}', [FotografiaController::class, 'procesarFormEditarFotografia'])
    ->name('editarfotografia')->middleware('chequeaprivilegio:admin_basico');
