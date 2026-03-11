<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
       try {
        /** @var \Laravel\Socialite\Two\AbstractProvider $driver */
        $driver = Socialite::driver('google');
        
        $googleUser = $driver->stateless()->user();

            $user = User::where('google_id', $googleUser->getId())
                        ->orWhere('email', $googleUser->getEmail())
                        ->first();

            if ($user) {
                $user->update(['google_id' => $googleUser->getId()]);
                
                Auth::login($user);
                session()->regenerate();
                
                return $this->redirectByRole($user->role);
            } else {
                session([
                    'google_id'    => $googleUser->getId(),
                    'google_email' => $googleUser->getEmail(),
                    'google_name'  => $googleUser->getName(),
                ]);

                return redirect()->route('register')->with('info', 'Silahkan pilih Tipe Pengguna dan Jurusan Anda.');
            }

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal login Google: ' . $e->getMessage());
        }
    }

public function redirectByRole($role)
{
    return match ($role) {
        'admin'    => redirect()->route('admin.dashboard'),
        'pimpinan' => redirect()->route('pimpinan.dashboard'),
        'kaproli'  => redirect()->route('kaproli.dashboard'),
        'pengguna' => redirect()->route('pengguna.dashboard'),
        default    => redirect('/'),
    };
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}