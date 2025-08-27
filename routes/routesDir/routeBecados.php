<?php

use App\Http\Controllers\becados\BecadosController;
use Illuminate\Support\Facades\Route;

Route::prefix('/usuario')->middleware('auth')->group(function(){
    Route::get('/', [BecadosController::class, 'index'])->name('becados.index');
});