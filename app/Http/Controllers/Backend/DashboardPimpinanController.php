<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

class DashboardPimpinanController extends Controller
{
     public function index()
    {
        // Menghitung total data untuk statistik (Card)
        // Gunakan DB::table jika Anda belum membuat Model-nya untuk menghindari error
        $users = User::count();
        $barang = Barang::count();
        
        // Menggunakan optional count agar jika tabel belum ada tidak langsung crash
        $peminjaman = DB::table('peminjaman')->count(); // Sesuaikan nama tabel
        //$pengajuan = DB::table('pengajuans')->count();   // Sesuaikan nama tabel
        $laporan_kerusakan = DB::table('laporan_kerusakan')->count(); // Sesuaikan nama tabel

        // Data untuk Chart Kondisi Barang
        $barangByKondisi = Barang::select('kondisi', DB::raw('COUNT(*) as total'))
            ->groupBy('kondisi')
            ->pluck('total', 'kondisi');

        return view('backend.pimpinan.dashboard', compact(
            'users', 
            'barang', 
            'peminjaman', 
           // 'pengajuan', 
            'laporan_kerusakan', 
            'barangByKondisi'
        ));
    }
}
