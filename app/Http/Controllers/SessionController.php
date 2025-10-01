<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $request->merge([
            'email' => strtolower(trim($request['email'])),
        ]);

        $validatedAttributes = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'min:8']
        ]);

        if (!Auth::attempt($validatedAttributes)) {
            throw ValidationException::withMessages([
                'email' => 'The provided credentials do not match our records.',
//                'password' => 'The provided credentials do not match our records.',
            ]);
        }

        $request->session()->regenerate();

        return redirect() -> intended('/');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
