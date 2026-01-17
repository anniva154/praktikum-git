<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        $role = auth()->user()->role;

        return match ($role) {
            'admin'    => redirect()->route('admin.dashboard'),
            'kaproli'  => redirect()->route('kaproli.dashboard'),
            'pimpinan' => redirect()->route('pimpinan.dashboard'),
            default    => redirect()->route('home'),
        };
    }



        return back()->withErrors([
            'email' => 'Email atau password salah',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
