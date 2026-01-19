<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Laboratorium;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BarangLabController extends Controller
{
    private function authorizeLab(Laboratorium $lab)
    {
        // ADMIN BEBAS AKSES
        if (auth()->user()->role === 'admin') {
            return;
        }
        // KAPROLI SESUAI JURUSAN
        if (
            auth()->user()->role === 'kaproli' &&
            $lab->id_jurusan !== auth()->user()->id_jurusan
        ) {
            abort(403, 'Anda tidak memiliki akses ke lab ini');
        }
    }
    public function index(Request $request, Laboratorium $lab)
    {
        if (auth()->user()->role === 'kaproli') {
            $this->authorizeLab($lab);
        }

        $barang = Barang::where('id_lab', $lab->id_lab)
            ->when($request->filled('kondisi'), function ($q) use ($request) {
                $q->where('kondisi', $request->kondisi);
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub->where('nama_barang', 'like', "%{$request->search}%")
                        ->orWhere('kode_barang', 'like', "%{$request->search}%");
                });
            })
            ->orderBy('nama_barang')
            ->paginate(10)
            ->withQueryString();

        return auth()->user()->role === 'admin'
            ? view('backend.admin.barang.index', compact('lab', 'barang'))
            : view('backend.kaproli.barang.index', compact('lab', 'barang'));
    }
    public function create(Laboratorium $lab)
    {
        $this->authorizeLab($lab);

        return view('backend.kaproli.barang.create', compact('lab'));
    }
    public function store(Request $request, Laboratorium $lab)
    {
        $this->authorizeLab($lab);

        $request->validate([
            'nama_barang' => 'required|string|max:100',
            'kode_barang' => 'required|string|max:100|unique:barang,kode_barang',
            'jumlah' => 'required|integer|min:1',
            'kondisi' => 'required|in:Baik,Dipinjam,Rusak,Dalam Perbaikan',
        ]);

        Barang::create([
            'id_lab' => $lab->id_lab,
            'id_jurusan' => $lab->id_jurusan,
            'nama_barang' => $request->nama_barang,
            'kode_barang' => $request->kode_barang,
            'jumlah' => $request->jumlah,
            'kondisi' => $request->kondisi,
        ]);

        return redirect()->route('kaproli.barang.index', $lab)
            ->with('success', 'Barang berhasil ditambahkan');
    }
    public function edit(Laboratorium $lab, $barang)
    {
        $this->authorizeLab($lab);

        $barang = Barang::where('id_barang', $barang)
            ->where('id_lab', $lab->id_lab)
            ->firstOrFail();

        return view('backend.kaproli.barang.edit', compact('lab', 'barang'));
    }
    public function update(Request $request, Laboratorium $lab, $barang)
    {
        // 🔐 Batasi KAPROLI ke lab miliknya
        if (auth()->user()->role === 'kaproli') {
            $this->authorizeLab($lab);
        }

        // 📦 Pastikan barang milik lab tsb
        $barang = Barang::where('id_barang', $barang)
            ->where('id_lab', $lab->id_lab)
            ->firstOrFail();

        // ✅ VALIDASI
        $request->validate([
            'nama_barang' => 'required|string|max:100',
            'kode_barang' => [
                'required',
                'string',
                'max:100',
                Rule::unique('barang', 'kode_barang')
                    ->ignore($barang->id_barang, 'id_barang'),
            ],
            'jumlah' => 'required|integer|min:1',
            'kondisi' => 'required|in:Baik,Dipinjam,Rusak,Dalam Perbaikan',
        ]);

        // 💾 UPDATE DATA
        $barang->update([
            'nama_barang' => $request->nama_barang,
            'kode_barang' => $request->kode_barang,
            'jumlah' => $request->jumlah,
            'kondisi' => $request->kondisi,
        ]);

        // REDIRECT SESUAI ROLE
        if (auth()->user()->role === 'admin') {
            return redirect()
                ->route('admin.barang.lab', $lab->id_lab)
                ->with('success', 'Barang berhasil diperbarui');
        }

        return redirect()
            ->route('kaproli.barang.index', $lab->id_lab)
            ->with('success', 'Barang berhasil diperbarui');
    }

    public function destroy(Laboratorium $lab, $barang)
    {
        if (auth()->user()->role === 'kaproli') {
            $this->authorizeLab($lab);
        }

        $barang = Barang::where('id_barang', $barang)
            ->where('id_lab', $lab->id_lab)
            ->firstOrFail();

        $barang->delete();

        return redirect()->back()->with('success', 'Barang berhasil dihapus');
    }
}
