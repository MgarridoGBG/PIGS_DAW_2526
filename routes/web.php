<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FotografiaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZonaPrivadaController;
use App\Http\Controllers\ReportajeController;
use App\Http\Controllers\PedidoController;


// Rutas de la aplicación, organizadas por funcionalidad y acceso (públicas, privadas, administración).
require __DIR__.'/aplicacion/servirfotosrepor.php';
require __DIR__.'/aplicacion/autenticacion.php';
require __DIR__.'/aplicacion/carrito.php';

require __DIR__.'/administracion/adminfotografias.php';
require __DIR__.'/administracion/adminreportajes.php';
require __DIR__.'/administracion/adminusuarios.php';
require __DIR__.'/administracion/adminformatos.php';
require __DIR__.'/administracion/adminsoportes.php';
require __DIR__.'/administracion/adminpedidos.php';
require __DIR__.'/administracion/adminetiquetas.php';
require __DIR__.'/administracion/admincitas.php';


//Ruta a la zona pública principal, pagina de inicio.
Route::get('/', function () {
    $usuarios = App\Models\User::all();
    return view('zonapublica.principal', ['usuarios' => $usuarios]);
})->name('zonapublica');


//Rutas publicas adicionales
// Ruta a la página de contacto
Route::get('/contacto', function () {
    return view('zonapublica.contacto');
})->name('contacto');
// Ruta a la página de política de cookies
Route::get('/cookies', function () {
    return view('zonapublica.cookies');
})->name('cookies');
// Ruta a la página de acerca de nosotros
Route::get('/about', function () {
    return view('zonapublica.about');
})->name('about');
// Ruta al manual de usuario online
Route::get('/manual', function () {
    return view('zonapublica.manual');
})->name('manual');



//Ruta a la zona privada (accediendo a /zonaprivada vía GET)
Route::get('/zonaprivada', [ZonaPrivadaController::class, 'cargarZonaPrivada'])
    ->middleware('auth')->name('zonaprivada');

// Mantenimiento de base de datos (solo admin_avanzado)

Route::match(['get', 'post'], '/filtrarclientesfantasma', [UserController::class, 'filtrarClientesFantasma'])
    ->name('filtrarclientesfantasma')->middleware('chequeaprivilegio:admin_avanzado');

Route::match(['get', 'post'], '/filtrarreportajesfantasma', [ReportajeController::class, 'buscarReportajesFantasma'])
    ->name('filtrarreportajesfantasma')->middleware('chequeaprivilegio:admin_avanzado');

Route::match(['get', 'post'], '/filtrarfotosfantasma', [FotografiaController::class, 'buscarFotosFantasma'])
    ->name('filtrarfotosfantasma')->middleware('chequeaprivilegio:admin_avanzado');

Route::match(['get', 'post'], '/filtrarpedidosfantasma', [PedidoController::class, 'buscarPedidosFantasma'])
    ->name('filtrarpedidosfantasma')->middleware('chequeaprivilegio:admin_avanzado');

