<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->middleware('auth')->group(function(){
    Route::post('/obtener-datos', [HomeController::class, 'datosDashboard'])->name('dashboard.datos');
});