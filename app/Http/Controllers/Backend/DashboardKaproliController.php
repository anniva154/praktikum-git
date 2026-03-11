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

    // Filter dasar barang berdasarkan jurusan Kaproli
    $queryBarang = Barang::where('id_jurusan', $idJurusan);

    // Hitung data untuk Chart (Status)
    $statusCounts = [
        'tersedia'   => (clone $queryBarang)->where('status', 'Tersedia')->count(),
        'hilang'     => (clone $queryBarang)->where('status', 'Hilang')->count(),
        'diperbaiki' => (clone $queryBarang)->where('status', 'Diperbaiki')->count(),
        'dipinjam'   => (clone $queryBarang)->where('status', 'Dipinjam')->count(),
    ];

    // Data Lab (ID 4 dan 5 untuk TKJ)
    $barang_lab1 = (clone $queryBarang)->where('id_lab', 4)->count();
    $barang_lab2 = (clone $queryBarang)->where('id_lab', 5)->count();
    $barang = $barang_lab1 + $barang_lab2;

    // Hitung Total Peminjaman & Laporan di Jurusan Terkait
    $peminjaman = Peminjaman::whereHas('barang', function ($q) use ($idJurusan) {
        $q->where('id_jurusan', $idJurusan);
    })->count();

    $laporan = LaporanKerusakan::whereHas('barang', function ($q) use ($idJurusan) {
        $q->where('id_jurusan', $idJurusan);
    })->count();

    // --- TAMBAHKAN QUERY INI UNTUK ANTREAN VALIDASI ---
    $antreanValidasi = Peminjaman::with(['user', 'laboratorium', 'barang'])
        ->whereHas('barang', function ($q) use ($idJurusan) {
            $q->where('id_jurusan', $idJurusan);
        })
        ->where('status', 'diajukan') // Sesuaikan dengan status di database Anda
        ->latest()
        ->take(5) // Ambil 5 data terbaru saja untuk dashboard
        ->get();

    return view('backend.kaproli.dashboard', compact(
        'barang',
        'barang_lab1',
        'barang_lab2',
        'peminjaman',
        'laporan',
        'statusCounts',
        'antreanValidasi' // Lempar variabel ini ke view
    ));
}
}