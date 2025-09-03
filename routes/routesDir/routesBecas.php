<?php

use App\Http\Controllers\becas\BecasController;
use Illuminate\Support\Facades\Route;

Route::prefix('/becas')->middleware('auth')->group(function(){
    Route::post('/obtener', [BecasController::class, 'getBecas'])->name('becas.obtener');
});