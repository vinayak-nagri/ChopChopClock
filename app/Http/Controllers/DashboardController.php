<?php

namespace App\Http\Controllers;

use App\Models\PomodoroSession;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user_id= auth()->id();

        $recentRecords = PomodoroSession::where('user_id', $user_id)
                         ->whereDate('started_at',today())
                         ->get();

        $defaultSettings = UserSetting::where('user_id', $user_id)->firstOrFail();

        $activeSession = PomodoroSession::where('user_id', $user_id)
                         ->whereIn('status',['running','paused'])
                         ->latest()
                         ->first();

        return view('dashboard', compact('recentRecords','defaultSettings', 'activeSession'));
    }
}
