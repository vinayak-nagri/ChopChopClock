<?php

use App\Models\PomodoroSession;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

function createUserWithSettings(): User
{
    $user = User::create([
        'first_name' => 'React',
        'last_name' => 'Tester',
        'email' => fake()->unique()->safeEmail(),
        'password' => Hash::make('password'),
    ]);

    $user->forceFill([
        'email_verified_at' => now(),
    ])->save();

    UserSetting::create([
        'user_id' => $user->id,
        'work_minutes' => 25,
        'short_break_minutes' => 5,
        'long_break_minutes' => 15,
        'timezone' => 'UTC',
    ]);

    return $user;
}

it('renders dashboard data for the React timer app', function () {
    $user = createUserWithSettings();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('id="dashboard-root"', false)
        ->assertSee('id="dashboard-props"', false)
        ->assertSee('"work_minutes":25', false);
});

it('renders history data for the React history app', function () {
    $user = createUserWithSettings();

    PomodoroSession::create([
        'user_id' => $user->id,
        'type' => 'work',
        'duration_minutes' => 25,
        'elapsed_seconds' => 1500,
        'status' => 'completed',
        'started_at' => now()->subHour(),
        'ended_at' => now()->subMinutes(35),
    ]);

    $this->actingAs($user)
        ->get('/history')
        ->assertOk()
        ->assertSee('id="history-root"', false)
        ->assertSee('id="history-props"', false)
        ->assertSee('"completed"', false)
        ->assertSee('"cancelled":[]', false);
});

it('renders settings data for the React settings form', function () {
    $user = createUserWithSettings();

    $this->actingAs($user)
        ->get('/settings')
        ->assertOk()
        ->assertSee('id="settings-root"', false)
        ->assertSee('id="settings-props"', false)
        ->assertSee('"long_break_minutes":15', false);
});
