<?php

use App\Http\Controllers\becados\BecadosController;
use Illuminate\Support\Facades\Route;

Route::prefix('/estudiante')->middleware('auth')->group(function(){
    Route::get('/', [BecadosController::class, 'index'])->name('becados.index');
    Route::post('/save', [BecadosController::class, 'saveEstudiante'])->name('becado.save');
});