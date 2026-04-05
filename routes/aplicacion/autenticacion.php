<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// Rutas de autenticación. Login y Logout
Route::get('/login', [LoginController::class, 'mostrar_form_login'])->name('formlogin');
Route::post('/login', [LoginController::class, 'funcion_login'])->name('login');
Route::get('/logout', [LoginController::class, 'funcion_logout'])->name('logout');

// Rutas para manejar errores de autenticación y privilegios
Route::get('/accesodenegado', function () {
    return view('errores.error', ['mensaje' => 'Acceso denegado']);
})->name('accesodenegado');

Route::get('/noautenticado', function () {
    return view('errores.error', ['mensaje' => 'No autenticado']);
})->name('noautenticado');
