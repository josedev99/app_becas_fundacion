<?php

use App\Http\Controllers\becados\BecadosController;
use App\Http\Controllers\becados\SeguimientoController;
use Illuminate\Support\Facades\Route;

Route::prefix('/estudiante')->middleware('auth')->group(function(){
    Route::get('/', [BecadosController::class, 'index'])->name('becados.index');
    Route::post('/save', [BecadosController::class, 'saveEstudiante'])->name('becado.save');
    Route::post('/listar', [BecadosController::class, 'listarEstudiantes'])->name('becado.listar');
    Route::post('/editar',[BecadosController::class, 'getEstudianteById'])->name('becado.edit');
    
    Route::post('/obtener-todos',[BecadosController::class, 'getBecadosAll'])->name('becado.getAll');
    Route::post('/destroy-becado',[BecadosController::class, 'destroyBecado'])->name('destroy.becado');
    //pdf expediente
    Route::post('/expediente-pdf',[BecadosController::class, 'printExpediente'])->name('becado.exp.pdf');
});

Route::prefix('/seguimiento')->middleware('auth')->group(function(){
    Route::get('/estudiante', [SeguimientoController::class, 'index'])->name('seguimiento.index');
    Route::post('/save', [SeguimientoController::class, 'saveSeguimiento'])->name('seguimiento.save');
    Route::post('/listar', [SeguimientoController::class, 'listarSeguimientos'])->name('seguimiento.listar');
    Route::post('/show', [SeguimientoController::class, 'showDetail'])->name('seguimiento.detalle');
    Route::post('delete',[SeguimientoController::class, 'destroySeguimiento'])->name('seguimiento.destroy');
});