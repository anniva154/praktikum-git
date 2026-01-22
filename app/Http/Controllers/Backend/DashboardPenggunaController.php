<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardPenggunaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('backend.pengguna.dashboard', [
            'tipe' => $user->tipe_pengguna, // guru / siswa
            'jurusan' => $user->id_jurusan
        ]);
    }
}
