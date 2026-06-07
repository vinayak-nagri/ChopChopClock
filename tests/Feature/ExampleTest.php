<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

it('keeps the original homepage calls to action for guests', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('href="/register"', false)
        ->assertSee('href="/login"', false)
        ->assertSee('Create Your Account')
        ->assertSee('Join ChopChopClock and take control of your time.')
        ->assertDontSee('Start Now');
});

it('shows dashboard calls to action for authenticated users', function () {
    $user = User::create([
        'first_name' => 'Homepage',
        'last_name' => 'Tester',
        'email' => 'homepage@example.test',
        'password' => Hash::make('password'),
    ]);

    $this->actingAs($user)
        ->get('/')
        ->assertOk()
        ->assertSee('href="/dashboard"', false)
        ->assertDontSee('href="/login"', false)
        ->assertSee('Start Now')
        ->assertDontSee('Create Your Account')
        ->assertDontSee('Join ChopChopClock and take control of your time.');
});
