<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
//Login
Route::get('/login',[LoginController::class,'index'])->name('app.login.index');
Route::post('/authUser',[LoginController::class,'login'])->name('app.login.auth');
//Route::get('/salir',[LogoutController::class,'logout'])->middleware('auth')->name('app.logout');


Route::get('/',[HomeController::class,'index'])->middleware('auth')->name('app.home');
