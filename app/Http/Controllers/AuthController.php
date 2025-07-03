<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    public function registerPost(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect('/home');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function loginPost(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required',
        ]);

        $loginInput = $request->input('email');
        $password = $request->input('password');

        $credentials_admin = [
            'name' => $loginInput,
            'password' => $password,
            'id' => 1,
        ];

        if (Auth::attempt($credentials_admin)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin');
        }

        $credentials_customer = [
            'email' => $loginInput,
            'password' => $password,
        ];

        if (Auth::attempt($credentials_customer)) {
            $request->session()->regenerate();
            return redirect()->intended('/home');
        }

        if (!filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            throw ValidationException::withMessages([
                'email' => 'The email must be a valid email address.',
            ]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
