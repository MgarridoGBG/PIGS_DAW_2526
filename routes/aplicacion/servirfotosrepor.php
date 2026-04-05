<?php

use App\Http\Controllers\FotografiaController;
use Illuminate\Support\Facades\Route;

// RUTAS PARA MOSTRAR FOTOGRAFÍAS Y REPORTAJES
// Rutas para servir archivos desde storage/private

Route::get(
    '/private/fotosreportajes/{rutaCorta}',
    [FotografiaController::class, 'servirFotoStorage']
)
    ->where('rutaCorta', '.*')   // <-- permite barras
    ->name('fotostorage')->middleware('auth');

// Ruta pública para servir fotos de reportajes públicos (sin requerir autenticación)
Route::get(
    '/public/fotosreportajes/{rutaCorta}',
    [FotografiaController::class, 'servirFotoStorage']
)
    ->where('rutaCorta', '.*')
    ->name('fotopublicastorage');

// Ruta para mostrar las fotos de un reportaje específico
Route::get('/reportaje/{id}/fotos', [FotografiaController::class, 'mostrarFotosReportaje'])
    ->name('reportajefotos');

// Ruta para mostrar la galeria de fotos publicas
Route::get('/fotospublicas', [FotografiaController::class, 'mostrarFotosPublicas'])
    ->name('fotospublicas');

// Ruta para mostrar una foto específica privada
Route::get('/foto/{id}', [FotografiaController::class, 'mostrarFoto'])
    ->name('mostrarfoto');

// Ruta para mostrar una foto específica publica
Route::get('/fotopublica/{id}', [FotografiaController::class, 'mostrarFotoPublica'])
    ->name('zonapublicamostrarfotopublica');

// Ruta para filtrar por etiqueta en la galería pública
Route::get('/filtrarporetiqueta', [FotografiaController::class, 'filtrarPorEtiqueta'])
    ->name('filtrarporetiqueta');
