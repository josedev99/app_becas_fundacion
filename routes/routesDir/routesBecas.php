<?php

use App\Http\Controllers\becas\BecasController;
use Illuminate\Support\Facades\Route;

Route::prefix('/becas')->middleware('auth')->group(function(){
    Route::get('/administrar', [BecasController::class, 'index'])->name('becas.index');
    Route::post('/obtener', [BecasController::class, 'getBecas'])->name('becas.obtener');
    Route::post('/save', [BecasController::class, 'save'])->name('becas.save');
    Route::post('/listar', [BecasController::class, 'listarBecas'])->name('becas.listar');
    Route::post('/editar', [BecasController::class, 'getBecaById'])->name('beca.edit');
    Route::post('delete',[BecasController::class, 'destroyBeca'])->name('beca.destroy');
});