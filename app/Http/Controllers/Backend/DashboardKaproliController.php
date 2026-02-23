<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\LaporanKerusakan;
use Illuminate\Support\Facades\DB;


class DashboardKaproliController extends Controller
{
// KaproliDashboardController.php
public function index()
{
    $idJurusan = auth()->user()->id_jurusan;

    $barang = Barang::where('id_jurusan', $idJurusan)->count();

    $peminjaman = Peminjaman::whereHas('barang', function ($q) use ($idJurusan) {
        $q->where('id_jurusan', $idJurusan);
    })->count();

    $laporan = LaporanKerusakan::whereHas('barang', function ($q) use ($idJurusan) {
        $q->where('id_jurusan', $idJurusan);
    })->count();

    return view('backend.kaproli.dashboard', compact(
        'barang',
        'peminjaman',
        'laporan'
    ));
}
}