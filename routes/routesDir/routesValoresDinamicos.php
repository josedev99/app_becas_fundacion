<?php

use App\Http\Controllers\ValFormDinmicoController;
use Illuminate\Support\Facades\Route;

Route::prefix('valores-dinamicos')->middleware('auth')->group(function(){
    Route::post('/save', [ValFormDinmicoController::class, 'save'])->name('val.form.save');
    Route::post('/obtener', [ValFormDinmicoController::class, 'getValores'])->name('val.form.get');
});