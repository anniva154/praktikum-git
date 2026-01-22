<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Barang;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    /**
     * 📄 LIST PEMINJAMAN (PENGGUNA)
     */
    public function index()
    {
        $peminjaman = Peminjaman::with(['barang.laboratorium'])
            ->where('id_user', auth()->id())
            ->latest()
            ->paginate(10);

        return view('backend.pengguna.peminjaman.index', compact('peminjaman'));
    }

    /**
     * 📝 FORM TAMBAH PEMINJAMAN
     */
    public function create()
    {
        // barang hanya dari jurusan user
        $barang = Barang::where('id_jurusan', auth()->user()->id_jurusan)
            ->where('kondisi', 'Baik')
            ->orderBy('nama_barang')
            ->get();

        return view('backend.pengguna.peminjaman.create', compact('barang'));
    }

    /**
     * 💾 SIMPAN PEMINJAMAN
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_barang'     => 'required|exists:barang,id_barang',
            'tgl_pinjam'    => 'required|date',
            'tgl_kembali'   => 'nullable|date|after_or_equal:tgl_pinjam',
            'jumlah_pinjam' => 'required|integer|min:1',
            'keterangan'    => 'nullable|string',
        ]);

        Peminjaman::create([
            'id_user'       => auth()->id(),
            'id_barang'     => $request->id_barang,
            'tgl_pinjam'    => $request->tgl_pinjam,
            'tgl_kembali'   => $request->tgl_kembali,
            'jumlah_pinjam' => $request->jumlah_pinjam,
            'status'        => 'diajukan',
            'keterangan'    => $request->keterangan,
        ]);

        return redirect()
            ->route('pengguna.peminjaman.index')
            ->with('success', 'Peminjaman berhasil diajukan');
    }
}
