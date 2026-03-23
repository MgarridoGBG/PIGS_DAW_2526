<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PedidoController;

// GESTION DE PEDIDOS
// Ruta para listar pedidos, solo accesible para roles con privilegio 'admin_basico'
Route::get('/listarpedidos', [App\Http\Controllers\PedidoController::class, 'listarPedidos'])
    ->name('listarpedidos')->middleware('chequeaprivilegio:admin_basico');

// Ruta para ver detalles de un pedido

Route::get('/verdetallepedido/{id}', [App\Http\Controllers\PedidoController::class, 'verDetallePedido'])
    ->name('verdetallepedido')->middleware('chequeaprivilegio:editar_propio');

// Ruta para borrar un item de un pedido (solo editar_propio)
Route::delete('/borraritempedido/{id}', [App\Http\Controllers\ItemController::class, 'borrarItemPedido'])
    ->name('borraritempedido')->middleware('chequeaprivilegio:editar_propio');

// Ruta para borrar un pedido (solo editar_propio)
Route::delete('/borrarpedido/{id}', [App\Http\Controllers\PedidoController::class, 'borrarPedido'])
    ->name('borrarpedido')->middleware('chequeaprivilegio:editar_propio');

// Ruta para filtrar pedidos (GET o POST)
Route::match(['get', 'post'], '/filtrarpedidos', [PedidoController::class, 'filtrarPedidos'])
    ->name('filtrarpedidos')->middleware('chequeaprivilegio:admin_basico');

// Ruta para mostrar formulario de edición de pedido
Route::get('/formeditarpedido/{id}', [PedidoController::class, 'mostrarFormEditarPedido'])
    ->name('formeditarpedido')->middleware('chequeaprivilegio:admin_basico');

// Ruta para procesar edición de pedido
Route::put('/editarpedido/{id}', [PedidoController::class, 'procesarFormEditarPedido'])
    ->name('editarpedido')->middleware('chequeaprivilegio:admin_basico');