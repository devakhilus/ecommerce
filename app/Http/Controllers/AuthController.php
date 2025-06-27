<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            // ✅ Require email verification only for non-admin users
            if (!$user->is_admin && !$user->hasVerifiedEmail()) {
                Auth::logout();
                return back()->withErrors(['email' => 'Please verify your email address first.']);
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'home_address' => 'nullable|string|max:255'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'home_address' => $request->home_address,
            'is_admin' => 0
        ]);

        // 🔔 Send email verification only to regular users
        $user->sendEmailVerificationNotification();

        return redirect('/login')->with('success', 'Registration successful. Please verify your email.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
