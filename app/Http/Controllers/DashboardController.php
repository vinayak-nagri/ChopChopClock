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

        $recentWorkSessions = PomodoroSession::where('user_id', $userId)
            ->whereDate('started_at', today())
            ->where('type', 'work')
            ->where('status', 'completed')
            ->get();

        $recentCancelledSessions = PomodoroSession::where('user_id', $userId)
            ->whereDate('started_at', today())
            ->where('type', 'work')
            ->where('status', 'cancelled')
            ->get();

        $countWorkSessions = $recentWorkSessions->count();

        $completedMinutes = (int) $recentWorkSessions->sum('duration_minutes');
        $cancelledSeconds = (int) $recentCancelledSessions->sum('elapsed_seconds');

        $formattedTotal = PomodoroSession::formatTotalTime($completedMinutes, $cancelledSeconds);

        $defaultSettings = UserSetting::where('user_id', $userId)->firstOrFail();

        $activeSession = PomodoroSession::where('user_id', $userId)
                         ->whereIn('status',['running','paused'])
                         ->latest()
                         ->first();

        return view('dashboard', compact('defaultSettings', 'activeSession'),
                    ['countWorkSessions' => $countWorkSessions,
                     'formattedTotal' => $formattedTotal,
                    ]);
    }

    public function getMetrics(Request $request)
    {
        $userId= auth()->id();

        $recentWorkSessions = PomodoroSession::where('user_id', $userId)
            ->whereDate('started_at', today())
            ->where('type', 'work')
            ->where('status', 'completed')
            ->get();

        $recentCancelledSessions = PomodoroSession::where('user_id', $userId)
            ->whereDate('started_at', today())
            ->where('type', 'work')
            ->where('status', 'cancelled')
            ->get();

        $countWorkSessions = $recentWorkSessions->count();

        $completedMinutes = (int) $recentWorkSessions->sum('duration_minutes');
        $cancelledSeconds = (int) $recentCancelledSessions->sum('elapsed_seconds');

        $formattedTotal = PomodoroSession::formatTotalTime($completedMinutes, $cancelledSeconds);

        return response() -> json([
            'count_work_sessions' => $countWorkSessions,
            'formatted_total' => $formattedTotal
        ]);
    }
}
