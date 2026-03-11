<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\Laboratorium;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    
    public function getBarangByLab($idLab)
    {
        return Barang::where('id_lab', $idLab)
            ->where('status', 'tersedia') 
            ->whereRaw('LOWER(kondisi) = ?', ['baik']) 
            ->where('jumlah', '>', 0)
            ->orderBy('nama_barang')
            ->get(['id_barang', 'nama_barang', 'jumlah']);
    }

    public function create()
    {
        $laboratorium = Laboratorium::orderBy('nama_lab', 'asc')->get();
        return view('backend.pengguna.peminjaman.create', compact('laboratorium'));
    }

       public function index(Request $request, $lab = null)
    {
        $user = auth()->user();
        $search = $request->input('search');
        $status = $request->input('status');

        // 1. PENGGUNA
        if ($user->role === 'pengguna') {
            $query = Peminjaman::with(['barang.laboratorium'])->where('id_user', $user->id);
            if ($search) $query->whereHas('barang', fn($q) => $q->where('nama_barang', 'like', "%{$search}%"));
            if ($status) $query->where('status', $status);
            $peminjaman = $query->latest()->paginate(10);
            return view('backend.pengguna.peminjaman.index', compact('peminjaman'));
        }

        // 2. ADMIN
        if ($user->role === 'admin') {
            $labs = Laboratorium::orderBy('nama_lab')->get();
            $query = Peminjaman::with(['barang.laboratorium', 'user']);
            if ($lab) {
                $query->whereHas('barang', fn($q) => $q->where('id_lab', $lab));
                $labModel = Laboratorium::find($lab);
            } else {
                $labModel = $labs->first();
                if ($labModel) $query->whereHas('barang', fn($q) => $q->where('id_lab', $labModel->id_lab));
            }
            if ($status) $query->where('status', $status);
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->whereHas('barang', fn($bq) => $bq->where('nama_barang', 'like', "%{$search}%"))
                      ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$search}%"));
                });
            }
            $peminjaman = $query->latest()->paginate(10);
            return view('backend.admin.peminjaman.index', ['peminjaman' => $peminjaman, 'lab' => $labModel, 'labs' => $labs]);
        }
 // 4. PIMPINAN
    if ($user->role === 'pimpinan') {
        // Pimpinan bisa melihat semua lab
        $labs = Laboratorium::orderBy('nama_lab')->get();
        $query = Peminjaman::with(['barang.laboratorium', 'user']);

        if ($lab) {
            $query->whereHas('barang', fn($q) => $q->where('id_lab', $lab));
            $labModel = Laboratorium::find($lab);
        } else {
            $labModel = $labs->first();
            if ($labModel) {
                $query->whereHas('barang', fn($q) => $q->where('id_lab', $labModel->id_lab));
            }
        }

        if ($status) $query->where('status', $status);
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('barang', fn($bq) => $bq->where('nama_barang', 'like', "%{$search}%"))
                  ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$search}%"));
            });
        }

        $peminjaman = $query->latest()->paginate(10);
        
        // Pastikan view diarahkan ke folder pimpinan
        return view('backend.pimpinan.peminjaman.index', [
            'peminjaman' => $peminjaman, 
            'lab' => $labModel, 
            'labs' => $labs
        ]);
    }

        // 3.  KAPROLI
        if ($user->role === 'kaproli') {
            $labs = Laboratorium::where('id_jurusan', $user->id_jurusan)->orderBy('nama_lab')->get();
            $query = Peminjaman::with(['barang.laboratorium', 'user']);
            if ($lab) {
                $labModel = Laboratorium::where('id_lab', $lab)->where('id_jurusan', $user->id_jurusan)->first();
                if (!$labModel) return redirect()->route('kaproli.peminjaman.index', [$labs->first()->id_lab ?? 0]);
            } else {
                $labModel = $labs->first();
            }
            if ($labModel) $query->whereHas('barang', fn($q) => $q->where('id_lab', $labModel->id_lab));
            if ($status) $query->where('status', $status);
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->whereHas('barang', fn($bq) => $bq->where('nama_barang', 'like', "%{$search}%"))
                      ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$search}%"));
                });
            }
            $peminjaman = $query->latest()->paginate(10);
            return view('backend.kaproli.peminjaman.index', ['peminjaman' => $peminjaman, 'lab' => $labModel, 'labs' => $labs]);
        }
        
    abort(403);
}
    

    // UPDATE STATUS (MANUAL DARI WEB)
   
public function updateStatus(Request $request, $id_lab, $id_peminjaman)
{
    $peminjaman = Peminjaman::findOrFail($id_peminjaman);
    $statusLama = $peminjaman->status;
    $statusBaru = $request->status;

    $peminjaman->status = $statusBaru;
    
    if ($statusBaru == 'dikembalikan') {
        $peminjaman->tanggal_dikembalikan = now();
    }
    $peminjaman->save();

    $barang = $peminjaman->barang; 

    if ($barang) {
        
        if ($statusLama != 'disetujui' && $statusBaru == 'disetujui') {
            
            if ($barang->jumlah >= $peminjaman->jumlah_pinjam) {
                $barang->decrement('jumlah', $peminjaman->jumlah_pinjam);
                
                $barang->update(['status' => 'dipinjam']);
            } else {
                return redirect()->back()->with('error', 'Stok barang tidak mencukupi untuk disetujui.');
            }
        }

        if ($statusLama != 'dikembalikan' && $statusBaru == 'dikembalikan') {
            $barang->increment('jumlah', $peminjaman->jumlah_pinjam);
            
            $barang->update(['status' => 'tersedia']);
        }
    }

    return redirect()->back()->with('success', 'Status updated & stok disesuaikan.');
}
    
public function konfirmasiKembali($id_peminjaman)
{
    $peminjaman = Peminjaman::findOrFail($id_peminjaman);
    if ($peminjaman->status !== 'disetujui') {
        return redirect()->back()->with('error', 'Peminjaman belum disetujui.');
    }

    DB::transaction(function () use ($peminjaman) {
        $barang = Barang::find($peminjaman->id_barang);
        $barang->increment('jumlah', $peminjaman->jumlah_pinjam);
        $barang->update(['status' => 'tersedia']); // Update status barang

        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_dikembalikan' => now(),
        ]);
    });

    return redirect()->back()->with('success', 'Barang berhasil dikembalikan dan stok bertambah.');
}

public function ajukanKembali($id)
{
    
    $peminjaman = Peminjaman::where('id_peminjaman', $id)
        ->where('id_user', auth()->id())
        ->firstOrFail();

    $peminjaman->update(['status' => 'menunggu_konfirmasi']);

    return redirect()->back()->with('success', 'Barang berhasil dikembalikan, mohon tunggu verifikasi Kaproli.');
}


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
            $kaproli = User::where('role', 'kaproli')->where('id_jurusan', $barang->laboratorium->id_jurusan)->first();
            if($kaproli && $kaproli->no_wa) {
                $pesan = "🔔 *PENGAJUAN PINJAM BARANG*\nID: #{$pinjam->id_peminjaman}\n\n *Peminjam:* " . auth()->user()->name . "\n *Barang:* {$barang->nama_barang}\n *Jumlah:* {$request->jumlah_pinjam}\n━━━━━━━━━━━━━━━━━━━━\n\nBalas chat ini dengan mengetik:\n✅ *Setujui*\n❌ *Tolak*";
                $this->sendWa($kaproli->no_wa, $pesan);
            }
        }
        return redirect()->route('pengguna.peminjaman.index')->with('success', 'Berhasil diajukan.');
    }

   
    public function webhookFonnte(Request $request)
    {
        $sender = $request->input('sender');
        $message = strtolower(trim($request->input('message')));
        $replyText = $request->input('reply_message') ?? $request->input('quotedMessage') ?? '';

        if (preg_match('/#(\d+)/', $message . ' ' . $replyText, $matches)) {
            $idFound = $matches[1];
            cache(["last_id_{$sender}" => $idFound], now()->addMinutes(10));
        }

        $id_peminjaman = cache("last_id_{$sender}");
        if ($id_peminjaman) {
            $kaproli = User::where('no_wa', $sender)->where('role', 'kaproli')->first();
            if ($kaproli) {
                $peminjaman = Peminjaman::find($id_peminjaman);
                if ($peminjaman && $peminjaman->status === 'diajukan') {
                    if ($message === 'setujui') {
                        DB::transaction(function () use ($peminjaman) {
                            $barang = Barang::find($peminjaman->id_barang);
                            if ($barang && $barang->jumlah >= $peminjaman->jumlah_pinjam) {
                                $barang->decrement('jumlah', $peminjaman->jumlah_pinjam);
                                $peminjaman->update(['status' => 'disetujui', 'diproses_at' => now()]);
                            }
                        });
                        cache()->forget("last_id_{$sender}");
                    } elseif ($message === 'tolak') {
                        $peminjaman->update(['status' => 'ditolak', 'diproses_at' => now()]);
                        cache()->forget("last_id_{$sender}");
                    }
                }
            }
        }
        return response()->json(['status' => 'success']);
    }

    private function sendWa($target, $message)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('target' => $target, 'message' => $message, 'countryCode' => '62'),
            CURLOPT_HTTPHEADER => array("Authorization: " . env('FONNTE_TOKEN')),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}