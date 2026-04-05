<?php

use App\Http\Controllers\CarritoController;
use Illuminate\Support\Facades\Route;

// GESTION DEL CARRITO (SESION)
// Ruta para mostrar formulario de nuevo item en curso
Route::post('/mostrarformcarrito/{id}', [CarritoController::class, 'mostrarFormCarrito'])
    ->name('mostrarformcarrito')->middleware('chequeaprivilegio:hacer_pedido');

// Ruta para procesar nuevo item en carrito
Route::post('/procesaritemcarrito', [CarritoController::class, 'registrarNuevoItemCarrito'])
    ->name('procesaritemcarrito')->middleware('chequeaprivilegio:hacer_pedido');

// Ruta para mostrar carrito (GET)
Route::get('/mostrarcarrito', [CarritoController::class, 'MostrarCarrito'])
    ->name('mostrarcarrito')->middleware('chequeaprivilegio:hacer_pedido');

// Ruta para eliminar item del carrito
Route::post('/borraritemcarrito', [CarritoController::class, 'borrarItemCarrito'])
    ->name('borraritemcarrito')->middleware('chequeaprivilegio:hacer_pedido');

// Ruta para vaciar el carrito
Route::post('/vaciarcarrito', [CarritoController::class, 'vaciarCarrito'])
    ->name('vaciarcarrito')->middleware('chequeaprivilegio:hacer_pedido');

// Ruta para enviar el carrito al pedido
Route::post('/procesarcarrito', [CarritoController::class, 'procesarCarrito'])
    ->name('procesarcarrito')->middleware('chequeaprivilegio:hacer_pedido');
