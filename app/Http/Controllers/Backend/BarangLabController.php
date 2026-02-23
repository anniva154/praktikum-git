<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Laboratorium;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class BarangLabController extends Controller
{

    public function getBarangByLab($id_lab)
{
    $user = auth()->user();

    $barang = Barang::where('id_lab', $id_lab)
        ->where('status', 'aktif')
        ->where('kondisi', '!=', 'rusak berat')
        ->when($user->role !== 'admin', function ($q) use ($user) {
            $q->where('id_jurusan', $user->id_jurusan);
        })
        ->orderBy('nama_barang')
        ->get();

    return response()->json($barang);
}
    private function authorizeLab(Laboratorium $lab): void
    {
        $user = auth()->user();

        // Admin bebas akses
        if ($user->role === 'admin') {
            return;
        }

        // Kaproli & Pengguna wajib sesuai jurusan
        if (in_array($user->role, ['kaproli', 'pengguna'], true)) {
            if ($lab->id_jurusan !== $user->id_jurusan) {
                abort(403, 'Anda tidak memiliki akses ke laboratorium ini.');
            }
        }
    }

    public function index(Request $request, Laboratorium $lab)
    {
        $this->authorizeLab($lab);

        $barang = Barang::where('id_lab', $lab->id_lab)
            ->when($request->filled('kondisi'), function ($q) use ($request) {
                $q->where('kondisi', $request->kondisi);
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub->where('nama_barang', 'like', "%{$request->search}%")
                        ->orWhere('kode_barang', 'like', "%{$request->search}%");
                });
            })
            ->orderBy('nama_barang')
            ->paginate(10)
            ->onEachSide(0)
            ->withQueryString();

        $view = match (auth()->user()->role) {
            'admin'    => 'backend.admin.barang.index',
            'kaproli'  => 'backend.kaproli.barang.index',
            'pimpinan'  => 'backend.pimpinan.barang.index',

            default    => 'backend.pengguna.barang.index',
        };

        return view($view, compact('lab', 'barang'));
    }

    public function create(Laboratorium $lab)
    {
        $this->authorizeLab($lab);

        return view('backend.kaproli.barang.create', compact('lab'));
    }


    public function edit(Laboratorium $lab, $Barang)
    
    {
        $this->authorizeLab($lab);

        $barang = Barang::where('id_barang', $Barang)
            ->where('id_lab', $lab->id_lab)
            ->firstOrFail();

        return view('backend.kaproli.barang.edit', compact('lab', 'barang'));
    }
    public function store(Request $request, Laboratorium $lab)
    {
        $this->authorizeLab($lab);

        $validated = $request->validate([
            'nama_barang' => 'required|string|max:100',
            'kode_barang' => 'required|string|max:100|unique:barang,kode_barang',
            'jumlah'      => 'required|integer|min:1',
            'kondisi'     => 'required|in:baik,rusak ringan,rusak berat',
            'status'      => 'required|in:aktif,tidak layak',
        ]);

        $barang = Barang::create([
            'id_lab'      => $lab->id_lab,
            'id_jurusan'  => $lab->id_jurusan,
            'nama_barang' => $validated['nama_barang'],
            'kode_barang' => $validated['kode_barang'],
            'jumlah'      => $validated['jumlah'],
            'kondisi'     => $validated['kondisi'],
            'status'      => $validated['status'],
        ]);

        // Notifikasi Tambah
        if (auth()->user()->role === 'kaproli') {
            $this->kirimWaKeAdmin($barang, $lab, 'Ditambahkan');
        }

        return redirect()->route('kaproli.barang.index', $lab->id_lab)->with('success', 'Barang berhasil ditambahkan.');
    }

    public function update(Request $request, Laboratorium $lab, $idBarang)
    {
        $this->authorizeLab($lab);

        $barang = Barang::where('id_barang', $idBarang)->where('id_lab', $lab->id_lab)->firstOrFail();

        $validated = $request->validate([
            'nama_barang' => 'required|string|max:100',
            'kode_barang' => [
                'required', 'string', 'max:100',
                Rule::unique('barang', 'kode_barang')->ignore($barang->id_barang, 'id_barang'),
            ],
            'jumlah'  => 'required|integer|min:1',
            'kondisi' => 'required|in:baik,rusak ringan,rusak berat',
            'status'  => 'required|in:aktif,tidak layak',
        ]);

        $barang->update($validated);

        // Notifikasi Perubahan
        if (auth()->user()->role === 'kaproli') {
            $this->kirimWaKeAdmin($barang, $lab, 'Diperbarui');
        }

        return redirect()->back()->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Laboratorium $lab, $idBarang)
    {
        $this->authorizeLab($lab);

        $barang = Barang::where('id_barang', $idBarang)->where('id_lab', $lab->id_lab)->firstOrFail();

        // Notifikasi Hapus (Dilakukan sebelum delete agar data barang masih bisa terbaca)
        if (auth()->user()->role === 'kaproli') {
            $this->kirimWaKeAdmin($barang, $lab, 'Dihapus');
        }

        $barang->delete();

        return redirect()->back()->with('success', 'Barang berhasil dihapus.');
    }

    /**
     * Fungsi Kirim WA Dinamis
     */
    private function kirimWaKeAdmin($barang, $lab, $aksi = 'Ditambahkan')
    {
        $user = auth()->user();

        // Ambil no WA admin
        $adminNumbers = User::where('role', 'admin')->whereNotNull('no_wa')->pluck('no_wa')->toArray();
        if (empty($adminNumbers)) return;

        $targets = implode(',', $adminNumbers);
        $lab->load('jurusan');

        // Pilih Emoji berdasarkan aksi
        $emoji = match($aksi) {
            'Ditambahkan' => '📢',
            'Diperbarui'  => '🟠',
            'Dihapus'      => '🔴',
            default       => '🔔'
        };

        $message = "{$emoji} *Data Barang {$aksi}*\n\n"
            . "*Oleh (Kaproli):* {$user->name}\n"
            . "*Jurusan:* " . ($lab->jurusan->nama_jurusan ?? '-') . "\n"
            . "*Lab:* {$lab->nama_lab}\n\n"
            . "*Nama Barang:* {$barang->nama_barang}\n"
            . "*Kode:* {$barang->kode_barang}\n"
            . "*Jumlah:* {$barang->jumlah}\n"
            . "*Kondisi:* {$barang->kondisi}\n"
            . "*Status:* {$barang->status}";

        try {
            Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN'),
            ])->post('https://api.fonnte.com/send', [
                'target'  => $targets,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            // Log error jika perlu
        }
    }
}
