<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportajeController;


// GESTION DE REPORTAJES
// Rutas para listar, editar, crear y borrar reportajes, solo accesible para roles con privilegio 'admin_basico'
Route::get('/listarreportajes', [ReportajeController::class, 'listarReportajes'])
    ->name('listarreportajes')->middleware('chequeaprivilegio:admin_basico');

Route::get('/formeditarreportaje/{id}', [ReportajeController::class, 'mostrarFormEditarReportaje'])
    ->name('formeditarreportaje')->middleware('chequeaprivilegio:admin_basico');

Route::put('/editarreportaje/{id}', [ReportajeController::class, 'procesarFormEditarReportaje'])
    ->name('editarreportaje')->middleware('chequeaprivilegio:admin_basico');

Route::delete('/borrarreportaje/{id}', [ReportajeController::class, 'borrarReportaje'])
    ->name('borrarreportaje')->middleware('chequeaprivilegio:admin_basico');

Route::match(['get', 'post'], '/filtrarreportajes', [ReportajeController::class, 'filtrarReportajes'])
    ->name('filtrarreportajes')->middleware('chequeaprivilegio:admin_basico');

Route::get('/nuevoreportaje', [ReportajeController::class, 'mostrarFormNuevoReportaje'])
    ->name('formnuevoreportaje')->middleware('chequeaprivilegio:admin_basico');

Route::post('/nuevoreportaje', [ReportajeController::class, 'registrarNuevoReportaje'])
    ->name('nuevoreportaje')->middleware('chequeaprivilegio:admin_basico');