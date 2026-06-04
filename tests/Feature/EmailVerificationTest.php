<?php

use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

uses(RefreshDatabase::class);

function createEmailVerificationUser(?string $verifiedAt = null): User
{
    $user = User::create([
        'first_name' => 'Verify',
        'last_name' => 'Tester',
        'email' => fake()->unique()->safeEmail(),
        'password' => Hash::make('password'),
    ]);

    if ($verifiedAt !== null) {
        $user->forceFill([
            'email_verified_at' => $verifiedAt,
        ])->save();
    }

    UserSetting::create([
        'user_id' => $user->id,
        'work_minutes' => 25,
        'short_break_minutes' => 5,
        'long_break_minutes' => 15,
        'timezone' => 'UTC',
    ]);

    return $user;
}

it('sends a verification email after registration', function () {
    Notification::fake();

    $response = $this->post('/register', [
        'first_name' => 'New',
        'last_name' => 'User',
        'email' => 'new-user@example.com',
        'timezone' => 'UTC',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $user = User::where('email', 'new-user@example.com')->firstOrFail();

    $response->assertRedirect(route('verification.notice'));
    $this->assertAuthenticatedAs($user);
    expect($user->hasVerifiedEmail())->toBeFalse();
    Notification::assertSentTo($user, VerifyEmail::class);
});

it('keeps unverified users out of the app routes', function () {
    $user = createEmailVerificationUser();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertRedirect(route('verification.notice'));
});

it('verifies a user through a signed verification link', function () {
    $user = createEmailVerificationUser();

    $url = URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), [
        'id' => $user->getKey(),
        'hash' => sha1($user->getEmailForVerification()),
    ], false);

    $this->actingAs($user)
        ->get($url)
        ->assertRedirect('/dashboard');

    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
});

it('resends the verification notification', function () {
    Notification::fake();
    $user = createEmailVerificationUser();

    $this->actingAs($user)
        ->post(route('verification.send'))
        ->assertRedirect()
        ->assertSessionHas('message', 'Verification link sent!');

    Notification::assertSentTo($user, VerifyEmail::class);
});

it('redirects unverified users to the verification notice after login', function () {
    $user = createEmailVerificationUser();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('verification.notice'));
});
