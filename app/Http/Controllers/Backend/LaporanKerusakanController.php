<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\LaporanKerusakan;
use App\Models\Barang;
use App\Models\Lab; // Sesuaikan jika nama modelnya Laboratorium
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanKerusakanController extends Controller
{
    /**
     * 📄 INDEX UNTUK PENGGUNA (Guru/Siswa)
     * Menampilkan laporan miliknya sendiri saja
     */
  public function index(Request $request, $lab = null)
{
    // 1. LOGIKA UNTUK KAPROLI & ADMIN (Jika ada parameter {lab} di URL)
    if ($lab) {
        $labData = $lab instanceof Lab ? $lab : Lab::findOrFail($lab);
        
        $query = LaporanKerusakan::with(['barang', 'laboratorium', 'user'])
            ->where('id_lab', $labData->id_lab);

        // Filter Pencarian Nama Barang
        if ($request->filled('search')) {
            $query->whereHas('barang', function($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->search . '%');
            });
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $laporan = $query->orderByDesc('tgl_laporan')->paginate(10);

        if (Auth::user()->role == 'admin') {
            return view('backend.admin.laporan.index', ['laporan' => $laporan, 'lab' => $labData]);
        }

        return view('backend.kaproli.laporan.index', ['laporan' => $laporan, 'lab' => $labData]);
    }

    // 2. LOGIKA UNTUK PENGGUNA (DITAMBAHKAN FILTER)
    $query = LaporanKerusakan::with(['barang', 'laboratorium'])
        ->where('id_user', Auth::id());

    // --- TAMBAHKAN LOGIKA FILTER DI SINI ---
    // Filter Pencarian Nama Barang
    if ($request->filled('search')) {
        $query->whereHas('barang', function($q) use ($request) {
            $q->where('nama_barang', 'like', '%' . $request->search . '%');
        });
    }

    // Filter Status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    // ---------------------------------------

    $laporan = $query->orderByDesc('tgl_laporan')->paginate(10);

    return view('backend.pengguna.laporan.index', compact('laporan'));
}

    /**
     * ➕ FORM TAMBAH (Biasanya untuk Pengguna)
     */
    public function create()
    {
        $laboratorium = Lab::orderBy('nama_lab')->get();
        return view('backend.pengguna.laporan.create', compact('laboratorium'));
    }

    /**
     * 💾 SIMPAN DATA
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_lab'      => 'required|exists:laboratorium,id_lab',
            'id_barang'   => 'required|exists:barang,id_barang',
            'tgl_laporan' => 'required|date',
            'keterangan'  => 'required|string|max:500',
            'foto'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Proteksi: Pastikan barang memang ada di lab tersebut
        $checkBarang = Barang::where('id_barang', $request->id_barang)
            ->where('id_lab', $request->id_lab)
            ->exists();

        if (!$checkBarang) {
            return back()->withErrors(['id_barang' => 'Barang tidak terdaftar di lab ini'])->withInput();
        }

        $fotoPath = $request->hasFile('foto') 
            ? $request->file('foto')->store('laporan-kerusakan', 'public') 
            : null;

        LaporanKerusakan::create([
            'id_user'     => Auth::id(),
            'id_lab'      => $request->id_lab,
            'id_barang'   => $request->id_barang,
            'tgl_laporan' => $request->tgl_laporan,
            'keterangan'  => $request->keterangan,
            'foto'        => $fotoPath,
            'status'      => 'diajukan' // Default status awal
        ]);

        return redirect()->route('pengguna.laporan.index')->with('success', 'Laporan berhasil dikirim');
    }

    /**
     * 📦 AJAX UNTUK PILIH BARANG
     */
    public function getBarangByLab($labId)
    {
        $barang = Barang::where('id_lab', $labId)
            ->select('id_barang', 'nama_barang')
            ->get();
        return response()->json($barang);
    }
}
    

