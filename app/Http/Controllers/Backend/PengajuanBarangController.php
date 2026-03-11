<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Laboratorium; 
use App\Models\PengajuanBarang;

class PengajuanBarangController extends Controller
{
  public function index($id_lab = null)
{
    $role = auth()->user()->role;

    // Pastikan $id_lab ada karena Pimpinan sekarang klik per lab
    // Jika tidak ada $id_lab di URL, kita bisa arahkan ke error atau ambil lab pertama
    if (!$id_lab) {
        return redirect()->back()->with('error', 'Silakan pilih laboratorium terlebih dahulu.');
    }

    $lab = Laboratorium::findOrFail($id_lab);

    // 1. Logic untuk Admin atau Pimpinan
    if ($role == 'admin' || $role == 'pimpinan') {
        $pengajuan = PengajuanBarang::with(['user', 'laboratorium', 'jurusan'])
            ->where('id_lab', $id_lab) // FILTER WAJIB berdasarkan klik sidebar
            ->when(request('search'), function($query) {
                $query->where('nama_barang', 'like', '%' . request('search') . '%');
            })
            ->when(request('status'), function($query) {
                $query->where('status_persetujuan', request('status'));
            })
            ->latest()
            ->paginate(10);
        
        // Pilih view
        $view = ($role == 'admin') ? 'backend.admin.pengajuan.index' : 'backend.pimpinan.pengajuan.index';
        return view($view, compact('pengajuan', 'lab'));
    } 
    
    // 2. Logic untuk Kaproli
    else {
        $pengajuan = PengajuanBarang::with(['user', 'laboratorium', 'jurusan'])
            ->where('id_lab', $id_lab)
            ->when(request('search'), function($query) {
                $query->where('nama_barang', 'like', '%' . request('search') . '%');
            })
            ->latest()
            ->paginate(10);

        return view('backend.kaproli.pengajuan.index', compact('pengajuan', 'lab'));
    }
}
public function show($id) // Parameter cuma satu: $id
{
    $role = auth()->user()->role;
    
    // Ambil data beserta relasi user dan laboratorium
    $pengajuan = PengajuanBarang::with(['user', 'laboratorium'])
                ->where('id_pengajuan', $id)
                ->first();

    if (!$pengajuan) {
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    // Filter keamanan untuk Kaproli
    if ($role == 'kaproli' && $pengajuan->id_user !== auth()->id()) {
        return response()->json(['message' => 'Akses dilarang'], 403);
    }

    return response()->json([
        'nama_barang'        => $pengajuan->nama_barang,
        'jumlah'             => $pengajuan->jumlah,
        'satuan'             => $pengajuan->satuan ?? 'Unit',
        'estimasi_harga'     => $pengajuan->estimasi_harga,
        'spesifikasi'        => $pengajuan->spesifikasi ?? '-',
        'catatan_pimpinan'   => $pengajuan->catatan_pimpinan,
        'urgensi'            => $pengajuan->urgensi,
        'alasan_kebutuhan'   => $pengajuan->alasan_kebutuhan,
        'status_persetujuan' => $pengajuan->status_persetujuan, // Sesuaikan nama kolom DB
        'user'               => ['name' => $pengajuan->user->name ?? 'Anonim'],
        'laboratorium'       => ['nama_lab' => $pengajuan->laboratorium->nama_lab ?? '-'],
    ]);
}
    
 public function validasi(Request $request, $id)
{
    $request->validate([
        'status_persetujuan' => 'required|in:Pending,Disetujui,Ditolak',
        'catatan_pimpinan'   => 'nullable|string|max:255',
    ]);

   
    $pengajuan = PengajuanBarang::with('user')->where('id_pengajuan', $id)->firstOrFail();
    
    $pengajuan->update([
        'status_persetujuan' => $request->status_persetujuan,
        'catatan_pimpinan'   => $request->catatan_pimpinan,
    ]);

    if ($pengajuan->user && $pengajuan->user->no_wa) {
        $emoji = ($request->status_persetujuan == 'Disetujui') ? '✅' : '❌';
        $catatan = $request->catatan_pimpinan ?? '-';
        
        $pesanBalasan = "📢 *HASIL VALIDASI PENGAJUAN*\n" .
                        "ID: #{$pengajuan->id_pengajuan}\n\n" .
                        "Halo *{$pengajuan->user->name}*,\n" .
                        "Pengajuan barang: *{$pengajuan->nama_barang}*\n" .
                        "Status: {$emoji} *{$request->status_persetujuan}*\n" .
                        "*Catatan:* {$catatan}\n" .
                        "━━━━━━━━━━━━━━━━━━━━\n\n" .
                        "Silahkan cek detailnya di Dashboard SIMLAB.";

        $this->sendWa($pengajuan->user->no_wa, $pesanBalasan);
    }

    return redirect()->back()->with('success', 'Status pengajuan berhasil diperbarui dan notifikasi telah dikirim ke Kaproli.');
}


    public function create($id_lab)
    {
        $lab = Laboratorium::findOrFail($id_lab);
        return view('backend.kaproli.pengajuan.create', compact('lab'));
    }

    public function store(Request $request, $id_lab)
{
    $lab = Laboratorium::findOrFail($id_lab);

    $request->validate([
        'nama_barang'      => 'required|string|max:255',
        'jumlah'           => 'required|integer|min:1',
        'satuan'           => 'required|string|max:255',
        'urgensi'          => 'required|in:Penting Sekali,Biasa,Persediaan',
        'alasan_kebutuhan' => 'required|string',
        'estimasi_harga'   => 'nullable|numeric',
        'spesifikasi'      => 'nullable|string',
    ]);

    $pengajuan = PengajuanBarang::create([
        'id_user'            => Auth::id(),
        'id_jurusan'         => $lab->id_jurusan, 
        'id_lab'             => $id_lab,
        'nama_barang'        => $request->nama_barang,
        'spesifikasi'        => $request->spesifikasi,
        'jumlah'             => $request->jumlah,
        'satuan'             => $request->satuan,
        'estimasi_harga'     => $request->estimasi_harga,
        'urgensi'            => $request->urgensi,
        'alasan_kebutuhan'   => $request->alasan_kebutuhan,
        'status_persetujuan' => 'Pending',
    ]);

    $namaKaproli = Auth::user()->name;
    $pesan = "📦 *PENGAJUAN BARANG BARU*\n" .
             "ID: #{$pengajuan->id_pengajuan}\n\n" .
             "*Kaproli:* {$namaKaproli}\n" .
             "*Barang:* {$request->nama_barang}\n" .
             "*Jumlah:* {$request->jumlah} {$request->satuan}\n" .
             "*Lab:* {$lab->nama_lab}\n" .
             "*Urgensi:* {$request->urgensi}\n" .
             "━━━━━━━━━━━━━━━━━━━━\n\n" .
             "Mohon Admin dan Pimpinan segera mengecek sistem untuk validasi.";

    $penerima = \App\Models\User::whereIn('role', ['admin', 'pimpinan'])->whereNotNull('no_wa')->get();
    foreach ($penerima as $user) {
        $this->sendWa($user->no_wa, $pesan);
    }

    return redirect()->route('kaproli.pengajuan.index', $id_lab)
                     ->with('success', 'Pengajuan berhasil dikirim & Notifikasi WA telah diteruskan.');
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

    public function edit($id_lab, $id_pengajuan)
    {
        $lab = Laboratorium::findOrFail($id_lab);
        $pengajuan = PengajuanBarang::where('id_pengajuan', $id_pengajuan)->firstOrFail();
        return view('backend.kaproli.pengajuan.edit', compact('lab', 'pengajuan'));
    }

    public function update(Request $request, $id_lab, $id_pengajuan)
    {
        $pengajuan = PengajuanBarang::where('id_pengajuan', $id_pengajuan)->firstOrFail();

        $request->validate([
            'nama_barang'      => 'required|string|max:255',
            'jumlah'           => 'required|integer|min:1',
            'satuan'           => 'required|string|max:255',
            'urgensi'          => 'required|in:Penting Sekali,Biasa,Persediaan',
            'alasan_kebutuhan' => 'required|string',
            'estimasi_harga'   => 'nullable|numeric',
            'spesifikasi'      => 'nullable|string',
        ]);

        $pengajuan->update([
            'nama_barang'      => $request->nama_barang,
            'spesifikasi'      => $request->spesifikasi,
            'jumlah'           => $request->jumlah,
            'satuan'           => $request->satuan,
            'estimasi_harga'   => $request->estimasi_harga,
            'urgensi'          => $request->urgensi,
            'alasan_kebutuhan' => $request->alasan_kebutuhan,
        ]);

        return redirect()->route('kaproli.pengajuan.index', $id_lab)
                         ->with('success', 'Pengajuan berhasil diperbarui!');
    }
    
    
}