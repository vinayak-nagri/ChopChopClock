<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PomodoroSessionController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserSettingController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});


Route::middleware('guest')->group(function () {
//show registration form
Route::get('/register',[RegisteredUserController::class, 'create'])->name('register');
//store registration information
Route::post('/register', [RegisteredUserController::class, 'store']);
//show login form
Route::get('/login',[SessionController::class, 'create'])->name('login');
//login action
Route::post('/login',[SessionController::class, 'store']);
});


Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function (Request $request) {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended('/dashboard')
            : view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect()->intended('/dashboard');
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Verification link sent!');
    })->middleware('throttle:6,1')->name('verification.send');

    //log out action
    Route::delete('/logout',[SessionController::class, 'destroy']);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
    Route::get('/dashboard/metrics',[DashboardController::class, 'getMetrics'])->name('dashboard.metrics');

    //Pomodoro Session Handling
    Route::post('/sessions/start', [PomodoroSessionController::class, 'start'])->name('sessions.start');
    Route::patch('/sessions/{session}/pause', [PomodoroSessionController::class, 'pause'])->name('sessions.pause');
    Route::patch('/sessions/{session}/resume',[PomodoroSessionController::class, 'resume'])->name('sessions.resume');
    Route::patch('/sessions/{session}/cancel',[PomodoroSessionController::class, 'cancel'])->name('sessions.cancel');
    Route::patch('/sessions/{session}/finish',[PomodoroSessionController::class, 'finish'])->name('sessions.finish');
    Route::delete('/sessions/{session}/destroy', [PomodoroSessionController::class, 'destroy'])->name('sessions.destroy');

    //History
    Route::get('/history', [PomodoroSessionController::class, 'history']);

    //User Settings
    Route::get('/settings', [UserSettingController::class, 'index']);
    Route::put('/settings', [UserSettingController::class, 'update']);
});
