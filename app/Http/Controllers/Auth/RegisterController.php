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
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'tipe_pengguna' => 'required|in:siswa,guru',
        'id_jurusan' => 'required|exists:jurusan,id_jurusan',
        'password' => 'required|confirmed|min:6',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'tipe_pengguna' => $request->tipe_pengguna,
        'id_jurusan' => $request->id_jurusan, // 🔥 WAJIB
        'password' => Hash::make($request->password),
        'status' => 'aktif',
    ]);

    return redirect()->route('login')->with('success', 'Registrasi berhasil');
}



}
