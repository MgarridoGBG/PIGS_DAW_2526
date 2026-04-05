<?php

use App\Http\Controllers\EtiquetaController;
use Illuminate\Support\Facades\Route;

// GESTION DE ETIQUETAS
// Ruta para añadir una etiqueta a una foto (solo admin_basico)
Route::put('/anadiretiquetafoto/{id}', [EtiquetaController::class, 'anadirEtiquetaFoto'])
    ->name('anadiretiquetafoto')->middleware('chequeaprivilegio:admin_basico');
// Ruta para eliminar una etiqueta de una foto (solo admin_basico)
Route::put('/borraretiquetafoto/{id}', [EtiquetaController::class, 'borrarEtiquetaFoto'])
    ->name('borraretiquetafoto')->middleware('chequeaprivilegio:admin_basico');

// Ruta para eliminar una etiqueta (solo admin_basico)
Route::post('/borrarEtiqueta', [EtiquetaController::class, 'borrarEtiqueta'])
    ->name('borrarEtiqueta')->middleware('chequeaprivilegio:admin_basico');

// Ruta para crear una nueva etiqueta (solo admin_basico)
Route::post('/crearEtiqueta', [EtiquetaController::class, 'crearEtiqueta'])
    ->name('crearEtiqueta')->middleware('chequeaprivilegio:admin_basico');
