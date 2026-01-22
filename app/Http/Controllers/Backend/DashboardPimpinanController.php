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
        return view('backend.pimpinan.dashboard', [
            'users' => User::count(),
            'barang' => Barang::count(),
            'barangByKondisi' => Barang::select('kondisi', DB::raw('COUNT(*) as total'))
                ->groupBy('kondisi')
                ->pluck('total', 'kondisi'),
        ]);
    }
}
