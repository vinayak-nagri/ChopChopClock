<?php

use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

//show registration form
Route::get('/register', [RegisteredUserController::class, 'create']) -> middleware('guest');
//store registration information
Route::post('/register', [RegisteredUserController::class, 'store']) -> middleware('guest');

//show login form
Route::get('/login',[SessionController::class, 'create']) -> middleware('guest');

//login action
Route::post('/login',[SessionController::class, 'store']) -> middleware('guest');

//log out action
Route::delete('/logout',[SessionController::class, 'destroy'])->middleware('auth');
