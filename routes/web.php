<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PomodoroSessionController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserSettingController;
use App\Models\UserSetting;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});


Route::middleware('guest')->group(function () {
//show registration form
Route::get('/register',[RegisteredUserController::class, 'create']);
//store registration information
Route::post('/register', [RegisteredUserController::class, 'store']);
//show login form
Route::get('/login',[SessionController::class, 'create'])->name('login');
//login action
Route::post('/login',[SessionController::class, 'store']);
});


//log out action
Route::delete('/logout',[SessionController::class, 'destroy'])->middleware('auth');

Route::get('/dashboard',[DashboardController::class,'index']) -> middleware ('auth')->name('dashboard');
Route::get('/dashboard/metrics',[DashboardController::class, 'getMetrics']) -> middleware('auth') -> name('dashboard.metrics');

//Pomodoro Session Handling
Route::middleware('auth')->group(function () {
    Route::post('/sessions/start', [PomodoroSessionController::class, 'start'])->name('sessions.start');
    Route::patch('/sessions/{session}/pause', [PomodoroSessionController::class, 'pause'])->name('sessions.pause');
    Route::patch('/sessions/{session}/resume',[PomodoroSessionController::class, 'resume'])->name('sessions.resume');
    Route::patch('/sessions/{session}/cancel',[PomodoroSessionController::class, 'cancel'])->name('sessions.cancel');
    Route::patch('/sessions/{session}/finish',[PomodoroSessionController::class, 'finish'])->name('sessions.finish');
    Route::delete('/sessions/{session}/destroy', [PomodoroSessionController::class, 'destroy'])->name('sessions.destroy');
});

//History
Route::get('/history', [PomodoroSessionController::class, 'history']) -> middleware('auth');

//User Settings
Route::get('/settings', [UserSettingController::class, 'index']) -> middleware('auth');
Route::put('/settings', [UserSettingController::class, 'update']) -> middleware('auth');
