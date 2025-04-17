<?php

namespace App\Http\Controllers;

use App\Events\UserStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Mengatur status online user setelah login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $user->update(['is_online' => true]);

            broadcast(new UserStatusUpdated($user))->toOthers();
            return redirect()->route('home');
        }

        return back()->with('error', 'Email atau password salah');
    }

    public function logout(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update(['is_online' => false]);

        broadcast(new UserStatusUpdated($user))->toOthers();
        Auth::logout();
        $request->session()->invalidate();

        return redirect()->route('login.form');
    }

    public function home()
    {
        return view('home');
    }
}
