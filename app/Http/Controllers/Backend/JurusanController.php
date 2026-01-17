<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;

class JurusanController extends Controller
{
    /**
     * Menampilkan daftar jurusan
     */
    public function index()
    {
        $jurusan = Jurusan::orderBy('id_jurusan', 'asc')->get();

        return view('backend.admin.jurusan.index', compact('jurusan'));
    }
}
