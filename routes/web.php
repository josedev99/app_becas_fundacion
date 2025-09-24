<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check()
        ? redirect('/')
        : redirect('/login');
});
//Login
Route::get('/login',[LoginController::class,'index'])->name('login');
Route::post('/authUser',[LoginController::class,'login'])->name('app.login.auth');
Route::get('/salir',[LogoutController::class,'logout'])->middleware('auth')->name('app.logout');
//Home vista
Route::get('/',[HomeController::class,'index'])->middleware('auth')->name('app.home');

require __DIR__.'/routesDir/routeBecados.php';
require __DIR__.'/routesDir/routesBecas.php';
require __DIR__.'/routesDir/routesDashboard.php';