<?php

namespace App\Http\Controllers;

use App\Models\User;
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
            'password' => ['required', 'min:8', 'confirmed']
        ]);

        $user = User::create($validatedAttributes);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect() -> intended('/');
    }
}
