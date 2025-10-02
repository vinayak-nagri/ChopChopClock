<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});


Route::middleware('guest')->group(function () {
//show registration form
Route::get('/register', [RegisteredUserController::class, 'create']);
//store registration information
Route::post('/register', [RegisteredUserController::class, 'store']);
//show login form
Route::get('/login',[SessionController::class, 'create'])->name('login');
//login action
Route::post('/login',[SessionController::class, 'store']);
});


//log out action
Route::delete('/logout',[SessionController::class, 'destroy'])->middleware('auth');

Route::get('/dashboard',[DashboardController::class,'index']) -> middleware ('auth');
