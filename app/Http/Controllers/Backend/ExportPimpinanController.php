<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Lab;
use App\Models\Barang;
use App\Models\Jadwal;
use App\Models\Pengajuan;
use App\Models\Peminjaman;
use App\Models\Laporan;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportPimpinanController extends Controller
{
    /**
     * EXPORT SEMUA DATA DALAM 1 FILE PDF
     * KHUSUS ROLE PIMPINAN
     */
    public function exportPdf()
    {
        // ===============================
        // AMBIL SEMUA DATA
        // ===============================
        $users = User::with('jurusan')->orderBy('name')->get();

        $labs = Lab::orderBy('nama_lab')->get();

        //$jadwal = Jadwal::with('lab')->orderBy('hari')->get();

        //$barang = Barang::with('lab')->orderBy('nama_barang')->get();

       // $pengajuan = Pengajuan::with('lab')->orderBy('created_at')->get();

        //$peminjaman = Peminjaman::with(['lab', 'user'])->orderBy('created_at')->get();

        //$laporan = Laporan::with('lab')->orderBy('created_at')->get();

        // ===============================
        // LOAD VIEW EXPORT
        // ===============================
        $pdf = Pdf::loadView(
            'backend.pimpinan.export',
            compact(
                'users',
                'labs',
                //'jadwal',
                //'barang',
                //'pengajuan',
                //'peminjaman',
                //'laporan'
            )
        )->setPaper('A4', 'portrait');

        // ===============================
        // DOWNLOAD
        // ===============================
        return $pdf->download(
            'laporan-sim-lab-smkn-3-bangkalan.pdf'
        );
    }
}
