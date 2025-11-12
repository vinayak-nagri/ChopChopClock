<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->merge([
            'email' => strtolower(trim($request['email'])),
        ]);

        $validatedAttributes = $request->validate([
            'first_name' => ['required','string', 'max:255'],
            'last_name' => ['required','string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email', 'max:255'],
            'password' => ['required', 'min:8', 'confirmed'],
            'timezone' => ['required']
        ]);

        $user = User::create($validatedAttributes);

        $defaultSettings = [
            'user_id' => $user->id,
            'work_minutes' => 25,
            'short_break_minutes' => 5,
            'long_break_minutes' => 15,
            'timezone' => $request->input('timezone'),
        ];

        UserSetting::create($defaultSettings);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect() -> intended('/');
    }
}
