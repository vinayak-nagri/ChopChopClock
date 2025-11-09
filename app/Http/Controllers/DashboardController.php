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
        $userId= auth()->id();

        $recentRecords = PomodoroSession::where('user_id', $userId)
                         ->whereDate('started_at',today())
                         ->get();

        $defaultSettings = UserSetting::where('user_id', $userId)->firstOrFail();

        $activeSession = PomodoroSession::where('user_id', $userId)
                         ->whereIn('status',['running','paused'])
                         ->latest()
                         ->first();

        return view('dashboard', compact('recentRecords','defaultSettings', 'activeSession'));
    }
}
