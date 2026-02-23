<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lab;
use App\Models\Jurusan;

class LabController extends Controller
{
    /**
     * Tampilkan daftar laboratorium + filter
     */
    public function index(Request $request)
    {
        $labs = Lab::with('jurusan')
            ->when($request->filled('jurusan'), function ($q) use ($request) {
                $q->where('id_jurusan', $request->jurusan);
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('nama_lab', 'like', '%' . $request->search . '%');
            })
            ->orderBy('nama_lab')
            ->paginate(10)
            ->withQueryString();

        $jurusan = Jurusan::orderBy('nama_jurusan')->get();

        return view('backend.admin.datalab.index', compact('labs', 'jurusan'));
    }

    /**
     * Form tambah lab
     */
    public function create()
    {
        $jurusan = Jurusan::orderBy('nama_jurusan')->get();

        return view('backend.admin.datalab.create', compact('jurusan'));
    }

    /**
     * Simpan lab baru
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_lab'   => 'required|string|max:100',
            'id_jurusan' => 'required|exists:jurusan,id_jurusan',
            'status'     => 'required|in:kosong,dipakai,perbaikan',
            'keterangan' => 'nullable|string|max:255',
        ]);

        try {
            Lab::create($data);

            return redirect()
                ->route('admin.lab.index')
                ->with('success', 'Laboratorium berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan laboratorium');
        }
    }

    /**
     * Form edit lab
     */
    public function edit($id)
    {
        $lab = Lab::find($id);

        if (!$lab) {
            return redirect()
                ->route('admin.lab.index')
                ->with('error', 'Laboratorium tidak ditemukan');
        }

        $jurusan = Jurusan::orderBy('nama_jurusan')->get();

        return view('backend.admin.datalab.edit', compact('lab', 'jurusan'));
    }

    /**
     * Update data lab
     */
    public function update(Request $request, $id)
    {
        $lab = Lab::find($id);

        if (!$lab) {
            return redirect()
                ->route('admin.lab.index')
                ->with('error', 'Laboratorium tidak ditemukan');
        }

        $data = $request->validate([
            'nama_lab'   => 'required|string|max:100',
            'id_jurusan' => 'required|exists:jurusan,id_jurusan',
            'status'     => 'required|in:kosong,dipakai,perbaikan',
            'keterangan' => 'nullable|string|max:255',
        ]);

        try {
            $lab->update($data);

            return redirect()
                ->route('admin.lab.index')
                ->with('success', 'Laboratorium berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui laboratorium');
        }
    }

    /**
     * Hapus lab
     */
    public function destroy($id)
    {
        $lab = Lab::find($id);

        if (!$lab) {
            return redirect()
                ->route('admin.lab.index')
                ->with('error', 'Laboratorium tidak ditemukan');
        }

        // contoh proteksi relasi (opsional)
        if (method_exists($lab, 'peminjaman') && $lab->peminjaman()->exists()) {
            return redirect()
                ->route('admin.lab.index')
                ->with('warning', 'Laboratorium tidak bisa dihapus karena masih digunakan');
        }

        try {
            $lab->delete();

            return redirect()
                ->route('admin.lab.index')
                ->with('success', 'Laboratorium berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.lab.index')
                ->with('error', 'Gagal menghapus laboratorium');
        }
    }
}
