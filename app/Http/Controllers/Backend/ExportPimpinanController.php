<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Laboratorium;
use App\Models\User;
use App\Models\Barang;
use App\Models\Peminjaman;
    // Tambahkan Use Model di bagian atas jika belum ada
use App\Models\PengajuanBarang;
use App\Models\LaporanKerusakan;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;



class ExportPimpinanController extends Controller
{
    // Laporan
    public function exportLaporan($id_lab)
    {
        $lab = Laboratorium::findOrFail($id_lab);
        $laporan = LaporanKerusakan::with(['user', 'barang'])
            ->whereHas('barang', function ($query) use ($id_lab) {
                $query->where('id_lab', $id_lab);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = Pdf::loadView('backend.pimpinan.laporan.export', compact('lab', 'laporan'))
            ->setPaper('A4', 'landscape');

        return $pdf->download('Laporan-Kerusakan-' . $lab->nama_lab . '.pdf');
    }

    // Peminjaman
    public function exportPeminjaman($id_lab)
    {

        $lab = Laboratorium::findOrFail($id_lab);


        $peminjaman = Peminjaman::with(['user', 'barang.laboratorium'])
            ->whereHas('barang', function ($query) use ($id_lab) {
                $query->where('id_lab', $id_lab);
            })
            ->orderBy('created_at', 'desc')
            ->get();


        $pdf = Pdf::loadView(
            'backend.pimpinan.peminjaman.export',
            compact('lab', 'peminjaman')
        )->setPaper('A4', 'landscape');

        return $pdf->download('riwayat-peminjaman-' . $lab->nama_lab . '.pdf');
    }

    // Barang
    public function exportBarang($id_lab)
    {

        $lab = Laboratorium::findOrFail($id_lab);


        $barang = Barang::with(['laboratorium', 'jurusan'])
            ->where('id_lab', $id_lab)
            ->orderBy('nama_barang')
            ->get();

        $pdf = Pdf::loadView(
            'backend.pimpinan.barang.export',
            compact('lab', 'barang')
        )->setPaper('A4', 'portrait');

        return $pdf->download('barang-lab-' . $lab->nama_lab . '.pdf');
    }

    // Pengguna
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


// ... (kode fungsi lainnya)

// Export Pengajuan Barang
public function exportPengajuan($id_lab)
{
    // Mengambil data laboratorium
    $lab = Laboratorium::findOrFail($id_lab);

    // Mengambil data pengajuan berdasarkan id_lab
    // Dilengkapi dengan relasi user, laboratorium, dan jurusan
    $pengajuan = PengajuanBarang::with(['user', 'laboratorium', 'jurusan'])
        ->where('id_lab', $id_lab)
        ->orderBy('created_at', 'desc')
        ->get();

    // Load view khusus export pengajuan
    // Menggunakan landscape karena kolom yang ditampilkan cukup banyak (alasan & catatan)
    $pdf = Pdf::loadView(
        'backend.pimpinan.pengajuan.export', 
        compact('lab', 'pengajuan')
    )->setPaper('A4', 'landscape');

    return $pdf->download('laporan-pengajuan-barang-' . $lab->nama_lab . '.pdf');
}
}
