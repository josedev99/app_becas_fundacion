<?php

use App\Http\Controllers\Permission\ModuloPermisoController;
use Illuminate\Support\Facades\Route;

Route::prefix('modulo')->middleware('auth')->group(function(){
    Route::post('/create', [ModuloPermisoController::class, 'createModulo'])->name('modulo.create');
    Route::post('/listar-modulos', [ModuloPermisoController::class, 'listarModulos'])->name('modulo.listar.dt');
    Route::post('delete-modulo', [ModuloPermisoController::class, 'deleteModulo'])->name('modulo.delete');
    //Routas permiso
    Route::post('/listar-permisos', [ModuloPermisoController::class, 'listarPermisos'])->name('modulo.listar.permiso.dt');
    Route::post('/create-permiso', [ModuloPermisoController::class, 'createPermiso'])->name('modulo.permiso.create');
    Route::post('delete-permiso', [ModuloPermisoController::class, 'deletePermiso'])->name('modulo.permiso.delete');
    //Routas para obtener los modulos para las cuentas y usuarios
    Route::post('obtener-permisos', [ModuloPermisoController::class, 'getModulosPermisos'])->name('modulo.permiso.obtener');
    Route::post('permisos-por-cuenta', [ModuloPermisoController::class, 'getModulosPermisoCuenta'])->name('permiso.cuenta.obtener');
});