<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Laboratorium;
use App\Models\User;
use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;



class ExportPimpinanController extends Controller
{
    /**
     * EXPORT LAPORAN SEMUA BARANG
     */
   public function exportBarang($id_lab)
{
    // Ambil lab sesuai id
    $lab = Laboratorium::findOrFail($id_lab);

    // Ambil barang sesuai lab
    $barang = Barang::with(['laboratorium', 'jurusan'])
                    ->where('id_lab', $id_lab)
                    ->orderBy('nama_barang')
                    ->get();

    $pdf = Pdf::loadView(
        'backend.pimpinan.barang.export',
        compact('lab', 'barang')
    )->setPaper('A4', 'portrait');

    return $pdf->download('barang-lab-'.$lab->nama_lab.'.pdf');
}

    /**
     * EXPORT LAPORAN SEMUA PENGGUNA
     */
    public function exportPenggunaPdf()
    {
        $users = User::with('jurusan')
            ->orderBy('name')
            ->get();

        $pdf = Pdf::loadView(
            'backend.pimpinan.pengguna.export',
            compact('users')
        )->setPaper('A4', 'portrait');

        return $pdf->download('laporan-pengguna-simlab.pdf');
        
    }
}
