<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Redirect setelah login sesuai role
     */
   protected function authenticated(Request $request, $user)
{
    return match ($user->role) {
        'admin'     => redirect()->route('admin.dashboard'),
        'kaproli'   => redirect()->route('kaproli.dashboard'),
        'pimpinan'  => redirect()->route('pimpinan.dashboard'),
        'pengguna'  => redirect()->route('pengguna.dashboard'),
        default     => abort(403, 'Role tidak dikenali'),
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

        return redirect('/login');
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}

