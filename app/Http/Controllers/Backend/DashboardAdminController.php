<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\LaporanKerusakan;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

class DashboardAdminController extends Controller
{
     public function index()
    {
        return view('backend.admin.dashboard', [
            'users' => User::count(),
            'peminjaman' => Peminjaman::count(),
            'laporan_kerusakan' => LaporanKerusakan::count(),
            'barang' => Barang::count(),
            'barangByKondisi' => Barang::select('kondisi', DB::raw('COUNT(*) as total'))
                ->groupBy('kondisi')
                ->pluck('total', 'kondisi'),
        ]);
    }
}
