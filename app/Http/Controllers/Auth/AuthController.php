<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Form login
     */
    public function loginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt login
        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Email atau password salah',
            ])->withInput();
        }

        // Regenerate session (security)
        $request->session()->regenerate();

        $user = auth()->user();

        // Redirect sesuai role
        return match ($user->role) {
            'admin'     => redirect()->route('admin.dashboard'),
            'kaproli'   => redirect()->route('kaproli.dashboard'),
            'pimpinan'  => redirect()->route('pimpinan.dashboard'),
            'pengguna'  => redirect()->route('pengguna.dashboard'),
            default     => redirect()->route('login')->withErrors([
                'email' => 'Role tidak dikenali',
            ]),
        };
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
