<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
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

require_once __DIR__.'/routesDir/routesModuloPermiso.php';
require_once __DIR__.'/routesDir/routeBecados.php';
require_once __DIR__.'/routesDir/routesBecas.php';
require_once __DIR__.'/routesDir/routesDashboard.php';
require_once __DIR__.'/routesDir/routesValoresDinamicos.php';

Route::prefix('/usuario')->middleware('auth')->group(function(){
    Route::get('/', [UserController::class, 'index'])->name('user.index');
    Route::post('/save', [UserController::class, 'save'])->name('user.save');
    Route::post('/listar', [UserController::class, 'listarAll'])->name('user.listar');
    Route::post('/obtener-por-id', [UserController::class, 'getUserById'])->name('user.by.id');
    Route::post('/obtener-empresas-usuario', [UserController::class, 'getUserEmpresas'])->name('user.empresas.obtener');
});