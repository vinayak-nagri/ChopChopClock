<?php

namespace App\Http\Controllers;

use App\Models\PomodoroSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class PomodoroSessionController extends Controller
{
    public function start(Request $request)
    {

        $data = $request->validate([
            'duration_minutes' => ['required','integer','min:1','max:240'],
            'type' => ['required','in:work,short_break,long_break'],
        ]);

        $session = PomodoroSession::create([
            'user_id' => auth()->id(),
            'type' => $data['type'],
            'duration_minutes' => $data['duration_minutes'],
            'elapsed_seconds' => 0,
            'status' => 'running',
            'started_at' => now(),
            'ended_at' => null,
            'meta' => null,
        ]);

        return redirect() -> back() -> with('session_id', $session -> id)
            -> with('session_status', $session->status)
            ->with('elapsed_seconds', $session->elapsed_seconds)
            ->with('type', $session->type)
            ->with('duration_minutes', $session->duration_minutes);
    }

    public function pause(PomodoroSession $session, Request $request)
    {

        $clientElapsed = $request -> input('elapsed_seconds');
        $durationSeconds = ($session->duration_minutes) * 60;

        $elapsedSeconds = max(0, min($clientElapsed, $durationSeconds));
        $session->elapsed_seconds = $elapsedSeconds;

        $session->status = 'paused';
        $session->save();

        return redirect() -> back() -> with('session_id', $session-> id)
            ->with('session_status', $session->status)
            ->with('elapsed_seconds', $session->elapsed_seconds)
            ->with('type', $session->type)
            ->with('duration_minutes', $session->duration_minutes);
    }

    public function resume(PomodoroSession $session)
    {
        if ($session->user_id != auth()->id())
        {
            abort(403);
        }

        $session->status = 'running';
        $session->save();

        return redirect() -> back() -> with('session_id', $session-> id)
            ->with('session_status', $session->status)
            ->with('elapsed_seconds', $session->elapsed_seconds)
            ->with('type', $session->type)
            ->with('duration_minutes', $session->duration_minutes);
    }

    public function cancel(PomodoroSession $session, Request $request)
    {
        if($session->user_id != auth()->id()) {
            abort(403);
        }

        $session->status = 'cancelled';
        $session->ended_at = now();

        $clientElapsed = $request -> input('elapsed_seconds');
        $durationSeconds = ($session->duration_minutes) * 60;
        $session->elapsed_seconds = max(0, min($clientElapsed,$durationSeconds));

        $session->save();

        return redirect() -> back() -> with('session_id', null)
            ->with('session_status', null)
            ->with('elapsed_seconds', null)
            ->with('type', $session->type)
            ->with('duration_minutes', $session->duration_minutes);
    }

    public function finish(PomodoroSession $session, Request $request)
    {
        if($session->user_id != auth()->id()){
            abort(403);
        }

        $durationSeconds = $session->duration_minutes * 60;
        $session->elapsed_seconds = $durationSeconds;
        $session->status = 'completed';
        $session->ended_at = now();
        $session->save();

        return redirect() -> back() -> with('session_id', $session->id)
            -> with('session_status', $session->status)
            -> with('elapsed_seconds', $session->elapsed_seconds)
            -> with('type', $session->type)
            ->with('duration_minutes', $session->duration_minutes);
    }

    public function destroy()
    {

    }
}


//$data = $request->validate([
//    'duration_minutes' => ['required', 'integer','min:1','max:240'],
//    'type' => ['required', 'in:work,short_break,long_break'],
//]);
//
//$session = PomodoroSession::create([
//    'user_id' => auth()->id(),
//    'type' => $data['type'],
//    'duration_minutes' => $data['duration_minutes'],
//    'elapsed_seconds' => 0,
//    'status' => 'running',
//    'started_at' => now(),
//    'ended_at' => null,
//    'meta' => null,
//]);
//
//return response()->json([
//    'session_id' => $session->id,
//    'user_id' => $session->user_id,
//    'type' => $session->type,
//    'status' => $session->status,
//    'duration_minutes' => $session->duration_minutes,
//    'elapsed_seconds' => $session->elapsed_seconds,
//    'started_at' => $session->started_at->setTimezone('Asia/Kolkata')->toIso8601String(),
//    'ends_at' => $session->started_at->setTimezone('Asia/Kolkata')->addMinutes($session->duration_minutes)->toIso8601String(),
//    'ended_at' => $session->ended_at,
//], 201);
