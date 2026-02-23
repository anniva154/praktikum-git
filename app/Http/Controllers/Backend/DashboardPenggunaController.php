<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Peminjaman; // Pastikan model ini ada
use App\Models\LaporanKerusakan; // Pastikan model ini ada

class DashboardPenggunaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Hitung total peminjaman milik user ini saja
        $totalPeminjamanUser = Peminjaman::where('id_user', $user->id)->count();

        // Hitung total laporan kerusakan milik user ini saja
        $kerusakanUser = LaporanKerusakan::where('id_user', $user->id)->count();

        return view('backend.pengguna.dashboard', [
            'tipe' => $user->tipe_pengguna,
            'jurusan' => $user->id_jurusan,
            'totalPeminjamanUser' => $totalPeminjamanUser,
            'kerusakanUser' => $kerusakanUser
        ]);
    }
}