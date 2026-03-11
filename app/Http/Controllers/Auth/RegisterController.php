<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * =========================
     * FORM REGISTER
     * =========================
     */
    public function showRegisterForm()
    {
        // 🔐 JIKA SUDAH LOGIN → REDIRECT
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return redirect('/admin/dashboard');
            }

            return redirect('/');
        }

        $jurusan = Jurusan::orderBy('nama_jurusan')->get();

        return view('auth.register', compact('jurusan'));
    }

    /**
     * =========================
     * PROSES REGISTER
     * =========================
     */
public function register(Request $request)
{
    $request->validate([
        'name'          => 'required|string|max:255',
        'email'         => 'required|string|email|max:255|unique:users',
        'password'      => 'required|string|min:8|confirmed',
        'tipe_pengguna' => 'required|in:siswa,guru',
        'id_jurusan'    => 'required|exists:jurusan,id_jurusan',
    ]);

    $user = User::create([
        'name'          => $request->name,
        'email'         => $request->email,
        'password'      => bcrypt($request->password),
        'tipe_pengguna' => $request->tipe_pengguna,
        'id_jurusan'    => $request->id_jurusan,
        'google_id'     => $request->google_id, // Disimpan jika ada
        'role'          => 'pengguna', // Role default pendaftar web
    ]);

    Auth::login($user);
    
    // Bersihkan session google agar tidak nyangkut jika login lagi nanti
    session()->forget(['google_id', 'google_email', 'google_name']);

    return redirect()->route('pengguna.dashboard')->with('success', 'Pendaftaran berhasil!');
}


}
