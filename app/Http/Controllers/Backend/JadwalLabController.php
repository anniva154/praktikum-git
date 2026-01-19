<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Laboratorium;
use App\Models\Jadwal;

class JadwalLabController extends Controller
{
    public function index(Laboratorium $lab)
    {
        // kalau sudah ada tabel jadwal
        $jadwal = Jadwal::where('laboratorium_id', $lab->id)->get();

        return view('backend.admin.jadwal.index', compact('lab', 'jadwal'));
    }
}
