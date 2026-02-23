<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\Laboratorium;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Penting untuk cek error
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    /**
     * 📄 GET BARANG AKTIF BERDASARKAN LAB
     */
    public function getBarangByLab($idLab)
    {
        return Barang::where('id_lab', $idLab)
            ->where('status', 'aktif')
            ->whereRaw('LOWER(kondisi) = ?', ['baik'])
            ->orderBy('nama_barang')
            ->get(['id_barang', 'nama_barang', 'jumlah']);
    }

    /**
     * 📄 LIST PEMINJAMAN
     */
   public function index(Request $request, $lab = null)
{
    $user = auth()->user();
    $search = $request->input('search');
    $status = $request->input('status');

    // --- LOGIKA UNTUK PENGGUNA ---
    if ($user->role === 'pengguna') {
        $query = Peminjaman::with(['barang.laboratorium'])
            ->where('id_user', $user->id);

        // Tambahkan Filter Search
        if ($search) {
            $query->whereHas('barang', fn($q) => $q->where('nama_barang', 'like', "%{$search}%"));
        }

        // Tambahkan Filter Status
        if ($status) {
            $query->where('status', $status);
        }

        $peminjaman = $query->latest()->paginate(10);
        return view('backend.pengguna.peminjaman.index', compact('peminjaman'));
    }

    // --- LOGIKA UNTUK ADMIN / KAPROLI ---
    if (in_array($user->role, ['admin', 'kaproli'])) {
        $labsQuery = Laboratorium::orderBy('nama_lab');
        if ($user->role === 'kaproli') {
            $labsQuery->where('id_jurusan', $user->id_jurusan);
        }
        $labs = $labsQuery->get();

        $query = Peminjaman::with(['barang.laboratorium', 'user']);

        // Logika Lab
        if ($lab) {
            $query->whereHas('barang', fn($q) => $q->where('id_lab', $lab));
            $labModel = Laboratorium::find($lab);
        } else {
            $labModel = $labs->first();
            $idLab = $labModel ? $labModel->id_lab : null;
            if ($idLab) {
                $query->whereHas('barang', fn($q) => $q->where('id_lab', $idLab));
            }
        }

        // Filter Global untuk Admin/Kaproli
        if ($status) $query->where('status', $status);
        if ($search) {
            $query->whereHas('barang', fn($q) => $q->where('nama_barang', 'like', "%{$search}%"));
        }

        $peminjaman = $query->latest()->paginate(10);
        return view('backend.kaproli.peminjaman.index', [
            'peminjaman' => $peminjaman,
            'lab' => $labModel,
            'labs' => $labs
        ]);
    }

    abort(403);
}

    /**
     * 📝 FORM CREATE
     */
    public function create()
    {
        $laboratorium = Laboratorium::where('id_jurusan', auth()->user()->id_jurusan)
            ->orderBy('nama_lab')
            ->get();

        return view('backend.pengguna.peminjaman.create', compact('laboratorium'));
    }

    /**
     * 💾 SIMPAN & KIRIM WA NOTIFIKASI
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_barang' => 'required|exists:barang,id_barang',
            'waktu_pinjam' => 'required|date',
            'jumlah_pinjam' => 'required|integer|min:1',
        ]);

        $pinjam = Peminjaman::create([
            'id_user' => auth()->id(),
            'id_barang' => $request->id_barang,
            'waktu_pinjam' => Carbon::parse($request->waktu_pinjam),
            'waktu_kembali' => $request->waktu_kembali ? Carbon::parse($request->waktu_kembali) : null,
            'jumlah_pinjam' => $request->jumlah_pinjam,
            'status' => 'diajukan',
            'keterangan' => $request->keterangan,
        ]);

        $barang = Barang::with('laboratorium.jurusan')->find($request->id_barang);
        
        if ($barang && $barang->laboratorium) {
            $kaproli = User::where('role', 'kaproli')
                           ->where('id_jurusan', $barang->laboratorium->id_jurusan)
                           ->first();

            if($kaproli && $kaproli->no_wa) {
                $pesan = "🔔 *PENGAJUAN PINJAM BARANG*\n"
                       . "ID: #{$pinjam->id_peminjaman}\n\n"
                       . "👤 *Peminjam:* " . auth()->user()->nama . "\n"
                       . "📦 *Barang:* {$barang->nama_barang}\n"
                       . "🔢 *Jumlah:* {$request->jumlah_pinjam}\n"
                       . "━━━━━━━━━━━━━━━━━━━━\n\n"
                       . "Balas chat ini dengan mengetik:\n"
                       . "✅ *Setujui*\n"
                       . "❌ *Tolak*";

                $this->sendWa($kaproli->no_wa, $pesan);
            }
        }

        return redirect()->route('pengguna.peminjaman.index')->with('success', 'Berhasil diajukan.');
    }

    /**
     * 🤖 WEBHOOK VALIDASI WA
     */
    public function webhookFonnte(Request $request)
{
    $sender = $request->input('sender');
    $message = strtolower(trim($request->input('message')));
    $replyText = $request->input('reply_message') ?? $request->input('quotedMessage') ?? '';

    // Ambil ID dari pesan yang masuk (jika ada tanda #)
    if (preg_match('/#(\d+)/', $message . ' ' . $replyText, $matches)) {
        $idFound = $matches[1];
        // Simpan ID ini di Cache selama 10 menit khusus untuk nomor pengirim ini
        cache(["last_id_{$sender}" => $idFound], now()->addMinutes(10));
        \Log::info("ID Terdeteksi: #$idFound. Disimpan di cache untuk pengirim: $sender");
    }

    // Ambil ID dari cache jika di pesan balasan tidak ada ID-nya
    $id_peminjaman = cache("last_id_{$sender}");

    \Log::info("DETEKSI AKSI -> Pengirim: $sender | Kata Kunci: $message | Menggunakan ID: #$id_peminjaman");

    if ($id_peminjaman) {
        $kaproli = User::where('no_wa', $sender)->where('role', 'kaproli')->first();
        
        if ($kaproli) {
            $peminjaman = Peminjaman::find($id_peminjaman);
            
            if ($peminjaman && $peminjaman->status === 'diajukan') {
                // Gunakan IF ELSE yang sangat ketat
                if ($message === 'setujui') {
                    DB::transaction(function () use ($peminjaman) {
                        $barang = Barang::find($peminjaman->id_barang);
                        if ($barang && $barang->jumlah >= $peminjaman->jumlah_pinjam) {
                            $barang->decrement('jumlah', $peminjaman->jumlah_pinjam);
                            $peminjaman->update(['status' => 'disetujui', 'diproses_at' => now()]);
                        }
                    });
                    \Log::info("HASIL AKHIR: #$id_peminjaman SETUJU");
                    cache()->forget("last_id_{$sender}"); // Hapus cache setelah sukses

                } elseif ($message === 'tolak') {
                    $peminjaman->update(['status' => 'ditolak', 'diproses_at' => now()]);
                    \Log::info("HASIL AKHIR: #$id_peminjaman TOLAK");
                    cache()->forget("last_id_{$sender}"); // Hapus cache setelah sukses
                }
            }
        }
    }

    return response()->json(['status' => 'success']);
}

    /**
     * ⚙️ UPDATE STATUS MANUAL
     */
    public function updateStatus(Request $request, $lab, $id_peminjaman)
    {
        $peminjaman = Peminjaman::findOrFail($id_peminjaman);
        $statusBaru = $request->status;
        $statusLama = $peminjaman->status;

        DB::transaction(function () use ($peminjaman, $statusBaru, $statusLama) {
            $barang = Barang::lockForUpdate()->find($peminjaman->id_barang);

            if ($statusBaru === 'disetujui' && $statusLama !== 'disetujui') {
                $barang->decrement('jumlah', $peminjaman->jumlah_pinjam);
            }
            if ($statusLama === 'disetujui' && in_array($statusBaru, ['dikembalikan', 'ditolak'])) {
                $barang->increment('jumlah', $peminjaman->jumlah_pinjam);
            }
            $peminjaman->update(['status' => $statusBaru, 'diproses_at' => now()]);
        });

        return redirect()->back()->with('success', 'Status diperbarui.');
    }

    /**
     * HELPER KIRIM WA
     */
    private function sendWa($target, $message)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => $target,
                'message' => $message,
                'countryCode' => '62', 
            ),
            CURLOPT_HTTPHEADER => array(
                "Authorization: " . env('FONNTE_TOKEN')
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}