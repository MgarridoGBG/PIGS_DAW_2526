<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// GESTION DE USUARIOS
// Edicion de usuarios por admininstrador

// Rutas para listar, filtrar, editar y borrar usuarios, solo accesible para roles con privilegio 'admin_avanzado'
Route::get('/listarusuarios', [UserController::class, 'listarUsuarios'])
    ->name('listarusuarios')->middleware('chequeaprivilegio:admin_avanzado');

Route::match(['get', 'post'], '/filtrarusuarios', [UserController::class, 'filtrarUsuarios'])
    ->name('filtrarusuarios')->middleware('chequeaprivilegio:admin_avanzado');

Route::get('/editarusuario/{id}', [UserController::class, 'mostrarFormEditarUser'])
    ->name('formeditarusuario')->middleware('chequeaprivilegio:admin_avanzado');

Route::put('/editarusuario/{id}', [UserController::class, 'procesarFormEditarUsuario'])
    ->name('editarusuario')->middleware('chequeaprivilegio:admin_avanzado');

// Edicion de su propio usuario
Route::get('/editarmiperfil', [UserController::class, 'mostrarFormEditarMiPerfil'])
    ->name('formeditarperfil')->middleware('chequeaprivilegio:editar_propio');

Route::patch('/editarmiperfil', [UserController::class, 'procesarFormEditarMiPerfil'])
    ->name('editarmiperfil')->middleware('chequeaprivilegio:editar_propio');

// Ruta para marcar/desmarcar el propio usuario para borrado (desde el dashboard cliente)
Route::post('/administracion/marcadoborrarpropia', [UserController::class, 'marcarBorrarPropia'])
    ->name('marcadoborrarpropia')->middleware('chequeaprivilegio:editar_propio');
// Redirección GET para la ruta de marcado borrado propia, para evitar errores al recargar la página
Route::get('/administracion/marcadoborrarpropia', function () {
    return redirect()->route('zonaprivada');
});

// Borrado de usuarios por administrador
Route::delete('/borrarusuario/{id}', [UserController::class, 'borrarUsuario'])
    ->name('borrarusuario')->middleware('chequeaprivilegio:admin_avanzado');

// Creación de nuevos usuarios por cualuier visitante o usuario autenticado
Route::get('/nuevousuario', [UserController::class, 'mostrarFormNuevoUser'])
    ->name('formnuevousuario');

Route::post('/nuevousuario', [UserController::class, 'registrarNuevoUser'])
    ->name('nuevousuario');
