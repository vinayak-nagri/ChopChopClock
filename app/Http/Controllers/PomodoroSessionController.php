<?php

namespace App\Http\Controllers;

use App\Models\PomodoroSession;
use App\Models\UserSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;


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

        return $this->respond($session, $request);
    }

    public function pause(PomodoroSession $session, Request $request)
    {
        $this->authorizeSession($session);

        $session->elapsed_seconds = $this->calcElapsedSeconds($session, $request);
        $session->status = 'paused';
        $session->save();

        return $this->respond($session, $request);
    }

    public function resume(PomodoroSession $session, Request $request)
    {
        $this->authorizeSession($session);

        $session->elapsed_seconds = $this->calcElapsedSeconds($session, $request);
        $session->status = 'running';
        $session->save();

        return $this->respond($session, $request);
    }

    public function cancel(PomodoroSession $session, Request $request)
    {
        $this->authorizeSession($session);

        $session->status = 'cancelled';
        $session->ended_at = now();
        $session->elapsed_seconds = $this->calcElapsedSeconds($session, $request);
        $session->save();

        return $this->respond($session, $request);
    }

    public function finish(PomodoroSession $session, Request $request)
    {
        $this->authorizeSession($session);

        $durationSeconds = $session->duration_minutes * 60;
        $session->elapsed_seconds = $durationSeconds;
        $session->status = 'completed';
        $session->ended_at = now();
        $session->save();

        return $this->respond($session, $request);
    }

    public function history()
    {
        $userId = auth()->id();
        $timezone = UserSetting::where('user_id',$userId)->pluck('timezone')->first();

        $records = PomodoroSession::where('user_id', $userId)
                   -> where('type', 'work')
                   -> get()
                   -> groupBy('status');

        $completedRecords = $records->get('completed',collect());
        $cancelledRecords = $records->get('cancelled',collect());

        $completedPaginator = PomodoroSession::where('user_id', $userId)
                              -> where('type','work')
                              -> where('status','completed')
                              -> orderBy('started_at', 'desc')
                              -> simplePaginate(5,['*'], 'completed_page');


        $cancelledPaginator = PomodoroSession::where('user_id', $userId)
            -> where('type','work')
            -> where('status','cancelled')
            -> orderBy('started_at', 'desc')
            -> simplePaginate(5,['*'], 'cancelled_page');

        $completedMinutes = (int) $completedRecords->sum('duration_minutes');
        $cancelledSeconds = (int) $cancelledRecords->sum('elapsed_seconds');

        $formattedTotal = PomodoroSession::formatTotalTime($completedMinutes, $cancelledSeconds);

        $totalDays = $records->flatten()
                     ->pluck('started_at')
                     ->map(fn($date) => $date -> toDateString())
                     ->unique()
                     ->count();

        $dates = $completedRecords->pluck('started_at')
            ->map(fn($date) => $date -> toDateString())
            ->unique();


        $streak = true; $streakCount=0;
        $i = Carbon::today();
        $date = $i->toDateString();

        while($streak)
        {
            if($dates->contains($date)){
                $streakCount += 1;
            } else {
                $streak = false;
            }
            $date = $i->subDay()->toDateString();
        }

        return view('history', compact('completedPaginator','cancelledPaginator' ),
                    ['formattedTotal' => $formattedTotal,
                     'totalDays' => $totalDays,
                     'streakCount' => $streakCount,
                     'timezone' => $timezone
                    ]);
    }

    public function destroy()
    {

    }

    private function toPayload(PomodoroSession $session): array
    {
        return [
            'session_id' => $session->id,
            'session_status' => $session->status,
            'elapsed_seconds' => $session->elapsed_seconds,
            'type' => $session->type,
            'duration_minutes' => $session->duration_minutes,
        ];
    }

    private function respond(PomodoroSession $session, Request $request)
    {
        $payload = $this->toPayload($session);

        if($request->wantsJson())
        {
            return response()->json($payload,200);
        }

        return redirect()->back();
    }

    private function calcElapsedSeconds(PomodoroSession $session, Request $request)
    {
        $durationMs = $session->duration_minutes * 60 * 1000;

        if($request->has('elapsed_ms')) {
            $clientElapsedMs = (int) $request->input('elapsed_ms', null);
            $clampedMs = max(0, min($clientElapsedMs, $durationMs));
            $clientElapsedSeconds = (int) floor($clampedMs / 1000);

            $session->elapsed_seconds = max($session->elapsed_seconds, $clientElapsedSeconds);
        }

        return $session->elapsed_seconds;
    }

    private function authorizeSession(PomodoroSession $session): void
    {
        if (($session->user_id != auth()->id()))
        {
            abort(403);
        }
    }

}

