<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\LaporanKerusakan;
use App\Models\Barang;
use App\Models\User;
use App\Models\Lab; // Sesuaikan jika nama modelnya Laboratorium
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanKerusakanController extends Controller
{
    
public function index(Request $request, $lab = null)
{
    // 1. LOGIKA UNTUK ROLE MANAJERIAL (Admin, Pimpinan, Kaproli)
    if ($lab) {
        $labData = $lab instanceof Lab ? $lab : Lab::findOrFail($lab);
        
        $query = LaporanKerusakan::with(['barang', 'laboratorium', 'user'])
            ->where('id_lab', $labData->id_lab);

        // Filter Pencarian
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

        // OTOMATIS MEMILIH VIEW BERDASARKAN ROLE
        $role = Auth::user()->role;
        return view("backend.$role.laporan.index", [
            'laporan' => $laporan, 
            'lab' => $labData
        ]);
    }

    // 2. LOGIKA UNTUK PENGGUNA BIASA
    $query = LaporanKerusakan::with(['barang', 'laboratorium'])
        ->where('id_user', Auth::id());

    // Filter Pencarian
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
    return view('backend.pengguna.laporan.index', compact('laporan'));
}


    public function create()
    {
        $laboratorium = Lab::orderBy('nama_lab')->get();
        return view('backend.pengguna.laporan.create', compact('laboratorium'));
    }

   
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
    $barang = Barang::with('laboratorium.jurusan')->find($request->id_barang);

    if (!$barang || $barang->id_lab != $request->id_lab) {
        return back()->withErrors(['id_barang' => 'Barang tidak terdaftar di lab ini'])->withInput();
    }

    $fotoPath = $request->hasFile('foto') 
        ? $request->file('foto')->store('laporan-kerusakan', 'public') 
        : null;

    $laporan = LaporanKerusakan::create([
        'id_user'     => Auth::id(),
        'id_lab'      => $request->id_lab,
        'id_barang'   => $request->id_barang,
        'tgl_laporan' => $request->tgl_laporan,
        'keterangan'  => $request->keterangan,
        'foto'        => $fotoPath,
        'status'      => 'diajukan' 
    ]);

    $userLapor = Auth::user()->name;
    $namaBarang = $barang->nama_barang;
    $namaLab = $barang->laboratorium->nama_lab;

    // 1. Pesan Khusus Kaproli
    $pesanKaproli = "🔔 *PEMBERITAHUAN KERUSAKAN (KAPROLI)*\n" .
                    "ID: #{$laporan->id_laporan}\n\n" .
                    "Ada laporan kerusakan di jurusan Anda:\n" .
                    "*Barang:* {$namaBarang}\n" .
                    "*Lokasi:* {$namaLab}\n" .
                    "*Pelapor:* {$userLapor}\n" .
                    "━━━━━━━━━━━━━━━━━━━━\n" .
                    "Silahkan koordinasi dengan Admin";

    // 2. Pesan Khusus Admin (Instruksi Validasi)
    $pesanAdmin = "⚠️ *PERLU VALIDASI (ADMIN)*\n" .
                  "ID: #{$laporan->id_laporan}\n\n" .
                  "Laporan baru masuk:\n" .
                  "*Barang:* {$namaBarang}\n" .
                  "*Keterangan:* {$request->keterangan}\n" .
                  "━━━━━━━━━━━━━━━━━━━━\n" .
                  "Silahkan login untuk mengubah status ke:\n" .
                  "🔄 *Perbaikan* atau ✅ *Selesai*";

    // Kirim ke Kaproli
    $idJurusan = $barang->laboratorium->id_jurusan;
    $kaproli = User::where('role', 'kaproli')->where('id_jurusan', $idJurusan)->first();
    if ($kaproli && $kaproli->no_wa) {
        $this->sendWa($kaproli->no_wa, $pesanKaproli);
    }

    // Kirim ke Admin
    $admins = User::where('role', 'admin')->whereNotNull('no_wa')->get();
    foreach ($admins as $admin) {
        $this->sendWa($admin->no_wa, $pesanAdmin);
    }

    return redirect()->route('pengguna.laporan.index')->with('success', 'Laporan berhasil dikirim.');
}
    /**
     * 📦 AJAX UNTUK PILIH BARANG
     */
   public function getBarangByLab($labId)
{
    $barang = Barang::where('id_lab', $labId)
        ->select('id_barang', 'nama_barang', 'kondisi', 'status') // Tambahkan ini
        ->get();
        
    return response()->json($barang);
}
   public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:diajukan,diproses,selesai' // Sesuaikan value dropdown Anda
    ]);

    // Cari laporan beserta data User yang melapor & Barang
    $laporan = LaporanKerusakan::with(['user', 'barang'])->where('id_laporan', $id)->firstOrFail();
    
    $laporan->update(['status' => $request->status]);

    // --- LOGIKA NOTIFIKASI KE USER PELAPOR ---
    if ($laporan->user && $laporan->user->no_wa) {
        $statusTeks = [
            'diajukan' => 'Menunggu Antrean ⏳',
            'diproses' => 'Sedang Dalam Perbaikan 🛠️',
            'selesai'  => 'Sudah Selesai Diperbaiki ✅'
        ];

        $statusSekarang = $statusTeks[$request->status] ?? $request->status;

        $pesanUser = "📢 *UPDATE LAPORAN KERUSAKAN*\n" .
                     "Halo *{$laporan->user->name}*,\n\n" .
                     "Laporan Anda untuk barang:\n" .
                     "*{$laporan->barang->nama_barang}*\n" .
                     "Saat ini berstatus: *{$statusSekarang}*\n" .
                     "━━━━━━━━━━━━━━━━━━━━\n" .
                     "Terima kasih telah melapor.";

        $this->sendWa($laporan->user->no_wa, $pesanUser);
    }

    return redirect()->back()->with('success', 'Status berhasil diperbarui dan pelapor telah dikabari.');
}

public function destroy($id)
{
    $laporan = LaporanKerusakan::findOrFail($id);

    // Hapus foto jika ada
    if ($laporan->foto) {
        Storage::disk('public')->delete($laporan->foto);
    }

    $laporan->delete();

    return redirect()->back()->with('success', 'Laporan kerusakan berhasil dihapus.');
}
private function sendWa($no_wa, $pesan)
{
    // Mengambil token dari .env agar lebih aman
    $token = env('FONNTE_TOKEN'); 
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.fonnte.com/send',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => array(
        'target' => $no_wa,
        'message' => $pesan,
        'countryCode' => '62', // Otomatis nambah 62 jika user input 08xxx
      ),
      CURLOPT_HTTPHEADER => array(
        "Authorization: $token"
      ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}
}
    

