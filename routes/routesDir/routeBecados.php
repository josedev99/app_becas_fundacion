<?php

use App\Http\Controllers\becados\BecadosController;
use App\Http\Controllers\becados\SeguimientoController;
use Illuminate\Support\Facades\Route;

Route::prefix('/estudiante')->middleware('auth')->group(function(){
    Route::get('/', [BecadosController::class, 'index'])->name('becados.index');
    Route::post('/save', [BecadosController::class, 'saveEstudiante'])->name('becado.save');
    Route::post('/listar', [BecadosController::class, 'listarEstudiantes'])->name('becado.listar');
    Route::post('/editar',[BecadosController::class, 'getEstudianteById'])->name('becado.edit');
});

Route::prefix('/seguimiento')->middleware('auth')->group(function(){
    Route::get('/estudiante', [SeguimientoController::class, 'index'])->name('seguimiento.index');
});