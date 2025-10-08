<?php

namespace App\Http\Controllers;

use App\Models\PomodoroSession;
use Illuminate\Http\Request;

class PomodoroSessionController extends Controller
{
    public function store(Request $request)
    {
        $duration_minutes = [
            'work' => 25,
            'short_break' => 5,
            'long_break' => 15
        ];
        PomodoroSession::create([
            'user_id' => auth()->id(),
            'type' => $request->input('type'),
            'duration_minutes' => $duration_minutes[$request->input('type')],
            'elapsed_seconds' => 0,
            'status' => 'running',
            'started_at' => now(),
            'ended_at' =>   null,
      ]);

    return redirect('/dashboard');
    }
}
