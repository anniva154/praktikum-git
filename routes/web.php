<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Backend\DashboardAdminController;
use App\Http\Controllers\Backend\DashboardPimpinanController;
use App\Http\Controllers\Backend\PenggunaAdminController;
use App\Http\Controllers\Backend\JurusanController;
use App\Http\Controllers\Backend\LabController;
use App\Http\Controllers\Backend\JadwalLabController;
use App\Http\Controllers\Backend\BarangLabController;
use App\Http\Controllers\Backend\ExportPimpinanController;
use App\Http\Controllers\Backend\DashboardPenggunaController;
use App\Http\Controllers\Backend\DashboardKaproliController;
use App\Http\Controllers\Backend\PeminjamanController;
use App\Http\Controllers\Backend\LaporanKerusakanController;
use App\Http\Controllers\Backend\PengajuanBarangController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\PasswordController;
use App\Http\Controllers\GoogleController;


use Illuminate\Support\Facades\Http;

use App\Http\Controllers\ChatbotController;

// Route untuk memproses chat dari frontend
Route::post('/chatbot/get-response', [ChatbotController::class, 'getResponse'])->name('chatbot.response');

// ==========================================
// 1. GUEST (Hanya bisa diakses jika belum login)
// ==========================================
Route::middleware('guest')->group(function () {
    
    // Login Manual
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    
    // Register Manual
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

    // Google Login Redirect
    Route::get('auth/google', [GoogleController::class, 'redirect'])->name('google.login');
    Route::get('auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

    // Forgot & Reset Password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    
  Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
    ->name('password.reset.update');
});

// ==========================================
// 2. AUTH (Hanya bisa diakses jika sudah login)
// ==========================================
Route::middleware('auth')->group(function () {

    // Logout Tunggal (Menggunakan GoogleController agar session bersih total)
    Route::post('/logout', [GoogleController::class, 'logout'])->name('logout');

   // Dashboard Pusat: Pengatur lalu lintas sesuai Role
    Route::get('/dashboard', function () {
        // Kita panggil fungsi redirectByRole dari GoogleController
        return app(GoogleController::class)->redirectByRole(Auth::user()->role);
    })->name('dashboard');

    // Rute-rute SIMLAB lainnya (Inventory, Lab, dll) taruh di bawah sini
});

// ==========================================
// 3. UTILITY
// ==========================================
Route::get('/force-logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/login')->with('success', 'Logged out successfully');
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        // DASHBOARD
        Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');

        // MASTER DATA
        Route::resource('pengguna', PenggunaAdminController::class);
        Route::resource('lab', LabController::class);
        Route::resource('jurusan', JurusanController::class)->only(['index']);

        // PENGAJUAN BARANG (Fix Error: admin.pengajuan.index)
        Route::prefix('pengajuan')->name('pengajuan.')->group(function() {
           Route::get('/', [PengajuanBarangController::class, 'index'])->name('index');
    Route::get('/{id}', [PengajuanBarangController::class, 'show'])->name('show'); // URL: /admin/pengajuan/1
            });

        // LAPORAN KERUSAKAN (Global)
        Route::prefix('laporan-kerusakan')->name('laporan-kerusakan.')->group(function() {
            Route::get('/', [LaporanKerusakanController::class, 'index'])->name('index'); 
            Route::patch('/{id}/status', [LaporanKerusakanController::class, 'updateStatus'])->name('update-status');
            Route::delete('/{id}', [LaporanKerusakanController::class, 'destroy'])->name('destroy');
        });

        // GROUP PER LAB
        Route::prefix('lab/{lab}')->group(function () {
            
            // Barang Lab
            Route::resource('barang', BarangLabController::class)->names([
                'index'   => 'barang.lab',
                'edit'    => 'barang.edit',
                'update'  => 'barang.update',
                'destroy' => 'barang.destroy',
            ])->parameters(['barang' => 'barang']);

            // Jadwal
            Route::get('jadwal', [JadwalLabController::class, 'index'])->name('jadwal.lab');
            
            // Peminjaman
            Route::prefix('peminjaman')->name('peminjaman.')->group(function() {
                Route::get('/', [PeminjamanController::class, 'index'])->name('lab');
                Route::get('/{peminjaman}', [PeminjamanController::class, 'show'])->name('show');
                Route::delete('/{peminjaman}', [PeminjamanController::class, 'destroy'])->name('destroy');
            });

            // Laporan Kerusakan spesifik lab
            Route::get('laporan', [LaporanKerusakanController::class, 'index'])->name('laporan.lab');
        });
    });
    

Route::group([
    'prefix' => 'kaproli',
    'as' => 'kaproli.',
    'middleware' => ['auth', 'role:kaproli']
], function () {

    // --- DASHBOARD ---
    Route::get('/dashboard', [DashboardKaproliController::class, 'index'])->name('dashboard');
    
    // --- MASTER DATA (READ ONLY) ---
    Route::resource('jurusan', JurusanController::class)->only(['index', 'show']);
    Route::resource('lab', LabController::class)->only(['index', 'show']);

    // --- MANAJEMEN LABORATORIUM ---
    Route::prefix('lab/{lab}')->group(function () {
        
        // 1. Inventaris Barang
        Route::resource('barang', BarangLabController::class);

        // 2. Pengajuan Barang Baru// 2. Pengajuan Barang Baru (Manual & Detail)
    Route::get('pengajuan/detail/{id}', [PengajuanBarangController::class, 'show'])->name('pengajuan.show'); // Route JSON
        Route::resource('pengajuan', PengajuanBarangController::class);

        // 3. Peminjaman Alat
        Route::patch('peminjaman/{peminjaman}/status', [PeminjamanController::class, 'updateStatus'])
             ->name('peminjaman.updateStatus');
        Route::resource('peminjaman', PeminjamanController::class);

        // 4. Laporan Kerusakan
        Route::resource('laporan', LaporanKerusakanController::class);
    });
});

        
Route::prefix('pimpinan')
    ->name('pimpinan.')
    ->middleware(['auth', 'role:pimpinan'])
    ->group(function () {

        // --- DASHBOARD ---
        Route::get('/dashboard', [DashboardPimpinanController::class, 'index'])->name('dashboard');

        // --- MANAJEMEN PENGGUNA ---
        Route::prefix('pengguna')->name('pengguna.')->group(function () {
            Route::get('/', [PenggunaAdminController::class, 'index'])->name('index');
            Route::get('/{user}', [PenggunaAdminController::class, 'show'])->name('show');
        });

        // --- MANAJEMEN LABORATORIUM (INVENTARIS & AKTIVITAS) ---
        // Inventaris Barang
        Route::get('/barang-lab/{lab}', [BarangLabController::class, 'index'])->name('barang.lab.index');
        
        // Jadwal & Peminjaman
        Route::get('/jadwal-lab/{lab}', [JadwalLabController::class, 'index'])->name('jadwal.lab');
        Route::get('/peminjaman-lab/{lab}', [PeminjamanController::class, 'index'])->name('peminjaman.lab');

        // Laporan Kerusakan
        Route::get('/laporan-lab/{lab}', [LaporanKerusakanController::class, 'index'])->name('laporan-lab');

       Route::prefix('pengajuan')->name('pengajuan.')->group(function() {
    // 1. Letakkan Detail & Validasi di urutan paling atas
    Route::get('/detail/{id}', [PengajuanBarangController::class, 'show'])->name('show'); 
    Route::patch('/validasi/{id}', [PengajuanBarangController::class, 'validasi'])->name('validasi');

    // 2. Route index dengan parameter opsional di bawahnya
    Route::get('/{id_lab?}', [PengajuanBarangController::class, 'index'])->name('index');
});

        // --- EXPORT PDF / EXCEL ---
        Route::prefix('export')->name('export.')->group(function () {
            Route::get('/pengguna', [ExportPimpinanController::class, 'exportPenggunaPdf'])->name('pengguna');
            Route::get('/barang/{id_lab}', [ExportPimpinanController::class, 'exportBarang'])->name('barang');
            Route::get('/peminjaman/{id_lab}', [ExportPimpinanController::class, 'exportPeminjaman'])->name('peminjaman');
            Route::get('/laporan/{id_lab}', [ExportPimpinanController::class, 'exportLaporan'])->name('laporan');
        Route::get('/pengajuan/{id_lab}', [ExportPimpinanController::class, 'exportPengajuan'])->name('pengajuan');
        
            });

    });
Route::prefix('pengguna')
    ->name('pengguna.')
    ->middleware(['auth', 'role:pengguna'])
    ->group(function () {
        // DASHBOARD
        Route::get('/dashboard', [DashboardPenggunaController::class, 'index'])
            ->name('dashboard');

        // PEMINJAMAN
        Route::resource('peminjaman', PeminjamanController::class)
            ->only(['index', 'create', 'store']);
        
        Route::post('/peminjaman/ajukan-kembali/{id}', [PeminjamanController::class, 'ajukanKembali'])
            ->name('peminjaman.ajukanKembali');

        // LAPORAN KERUSAKAN
        Route::resource('laporan', LaporanKerusakanController::class)
            ->only(['index', 'create', 'store']);

        // AJAX DATA FETCHING
        Route::get('/barang-by-lab/{lab}', [BarangLabController::class, 'getBarangByLab'])
            ->name('barang.by-lab');
        
        Route::get('/laporan/barang-by-lab/{lab}', [LaporanKerusakanController::class, 'getBarangByLab'])
            ->name('laporan.get-barang');
            
        // INFORMASI BARANG & JADWAL
        Route::get('/barang/{lab}', [BarangLabController::class, 'index'])
            ->name('barang.index');

        // Melihat jadwal penggunaan laboratorium
        Route::get('/jadwal-lab/{lab}', [JadwalLabController::class, 'pengguna'])
            ->name('jadwal.lab');
            
    });

Route::middleware(['auth'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});


Route::get('/profile/password', [PasswordController::class, 'edit'])
    ->name('password.edit');

Route::post('/profile/password', [PasswordController::class, 'update'])
    ->name('password.update');


/*
|--------------------------------------------------------------------------
| Landingpage ROUTE
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
