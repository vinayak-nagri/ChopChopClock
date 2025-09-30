<?php

namespace Database\Seeders;

use App\Models\PomodoroSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PomodoroSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        if ($user) {
            PomodoroSession::create([
                'user_id' => $user->id,
                'type' => 'work',
                'duration_minutes' => 25,
                'elapsed_seconds' => 1500,
                'status' => 'completed',
                'started_at' => Carbon::now()->subHours(3),
                'ended_at' => Carbon::now()->subHours(3)->addMinutes(25)
            ]);

            PomodoroSession::create([
                'user_id' => $user->id,
                'type' => 'short_break',
                'duration_minutes' => 5,
                'elapsed_seconds' => 300,
                'status' => 'completed',
                'started_at' => Carbon::now()->subHours(2),
                'ended_at' => Carbon::now()->subHours(2)->addMinutes(5),
            ]);

            PomodoroSession::create([
                'user_id' => $user->id,
                'type' => 'work',
                'duration_minutes' => 25,
                'elapsed_seconds' => 1200, // ended early
                'status' => 'completed',
                'started_at' => Carbon::now()->subHour(),
                'ended_at' => Carbon::now()->subHour()->addMinutes(20),
            ]);
        }
    }
}
