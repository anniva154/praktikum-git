<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
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
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\PasswordController;
use App\Http\Controllers\Backend\GoogleController;


use Illuminate\Support\Facades\Http;


// =======================
// GUEST (belum login)
// =======================
Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');

    // Register
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

});

// =======================
// LOGOUT (harus login)
// =======================
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
Route::get('/force-logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return 'Logged out successfully';
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardAdminController::class, 'index'])
            ->name('dashboard');
        Route::resource('pengguna', PenggunaAdminController::class);
        Route::resource('jurusan', JurusanController::class)->only(['index']);
        Route::resource('lab', LabController::class);

        // Jadwal Lab
        Route::get('jadwal-lab/{id_lab}', [JadwalLabController::class, 'index'])
            ->name('jadwal.lab');

        Route::get('barang-lab/{lab}', [BarangLabController::class, 'index'])
            ->name('barang.lab');

        Route::get('barang-lab/{lab}/{barang}/edit', [BarangLabController::class, 'edit'])
            ->name('barang.edit');

        Route::put('barang-lab/{lab}/{barang}', [BarangLabController::class, 'update'])
            ->name('barang.update');

        Route::delete('barang-lab/{lab}/{barang}', [BarangLabController::class, 'destroy'])
            ->name('barang.destroy');

            Route::get('peminjaman-lab/{id_lab}', [PeminjamanController::class, 'index'])
            ->name('peminjaman.lab');
             Route::get('laporan-lab/{id_lab}', [LaporanKerusakanController::class, 'indexAdmin'])
            ->name('laporan.lab');

    });


Route::prefix('kaproli')
    ->name('kaproli.')
    ->middleware(['auth', 'role:kaproli'])
    ->group(function () {

        Route::get('/dashboard', [DashboardKaproliController::class, 'index'])->name('dashboard');
        Route::resource('jurusan', JurusanController::class)->only(['index', 'show']);
        Route::resource('lab', LabController::class)->only(['index', 'show']);

        Route::prefix('lab/{lab}')->group(function () {
            
            // --- BARANG ---
            Route::resource('barang', BarangLabController::class)
                ->parameters(['barang' => 'id_barang']);

            // --- PEMINJAMAN (TARUH UPDATE STATUS DI ATAS RESOURCE) ---
            Route::patch('peminjaman/{id_peminjaman}/status', [PeminjamanController::class, 'updateStatus'])
                ->name('peminjaman.updateStatus');

            Route::resource('peminjaman', PeminjamanController::class)
                ->parameters(['peminjaman' => 'id_peminjaman']);

            // --- JADWAL, PENGAJUAN, DLL ---
            Route::resource('jadwal', JadwalController::class)->parameters(['jadwal' => 'id_jadwal']);
            Route::resource('pengajuan', PengajuanBarangController::class)->parameters(['pengajuan' => 'id_pengajuan']);
            Route::resource('laporan', LaporanKerusakanController::class)->parameters(['laporan' => 'id_laporan']);
        });
    });

        
Route::prefix('pimpinan')
    ->name('pimpinan.')
    ->middleware(['auth', 'role:pimpinan'])
    ->group(function () {

        // =======================
        // DASHBOARD
        // =======================
        Route::get('/dashboard', [DashboardPimpinanController::class, 'index'])
            ->name('dashboard');

 
Route::get('/export/barang/{id_lab}', 
    [ExportPimpinanController::class, 'exportBarang']
)->name('export.barang');


Route::get('/export/pengguna', 
    [ExportPimpinanController::class, 'exportPenggunaPdf']
)->name('export.pengguna');

        // DATA PENGGUNA (VIEW)
        // =======================
        Route::get('/pengguna', [PenggunaAdminController::class, 'index'])
            ->name('pengguna.index');

        Route::get('/pengguna/{user}', [PenggunaAdminController::class, 'show'])
            ->name('pengguna.show');

        // =======================
        // JADWAL LAB
        // =======================
        Route::get('/jadwal-lab/{lab}', [JadwalLabController::class, 'index'])
            ->name('jadwal.lab');

        // =======================
        Route::get('/barang-lab/{lab}', [BarangLabController::class, 'index'])
    ->name('barang.lab.index');


        // =======================
        // PENGAJUAN BARANG
        // =======================
        //Route::get('/pengajuan/{lab}', [PengajuanBarangController::class, 'index'])
            //->name('pengajuan.lab');

        // =======================
        // PEMINJAMAN
        // =======================
        //Route::get('/peminjaman/{lab}', [PeminjamanController::class, 'index'])
            //->name('peminjaman.lab');

        // =======================
        // LAPORAN / KERUSAKAN
        // =======================
        //Route::get('/laporan/{lab}', [LaporanController::class, 'index'])
            //->name('laporan.lab');
    });



Route::prefix('pengguna')
    ->name('pengguna.')
    ->middleware(['auth', 'role:pengguna'])
    ->group(function () {

        // ======================
        // DASHBOARD
        // ======================
        Route::get('/dashboard', [DashboardPenggunaController::class, 'index'])
            ->name('dashboard');

        // ======================
        // PEMINJAMAN
        // ======================
        Route::resource('peminjaman', PeminjamanController::class)
            ->only(['index', 'create', 'store']);

        // ======================
        // LAPORAN KERUSAKAN
        // ======================
        Route::resource('laporan', LaporanKerusakanController::class)
            ->only(['index', 'create', 'store']);

        // ======================
        // BARANG PER LAB (AJAX – DIPAKAI SEMUA)
        // ======================
        Route::get(
            '/barang-by-lab/{lab}',
            [BarangLabController::class, 'getBarangByLab']
        )->name('barang.by-lab');
        // KHUSUS LAPORAN KERUSAKAN – TANPA FILTER
Route::get(
    '/laporan/barang-by-lab/{lab}',
    [LaporanKerusakanController::class, 'getBarangByLab']
)->name('laporan.get-barang');


        // ======================
        // BARANG & JADWAL
        // ======================
        Route::get('/barang/{lab}', [BarangLabController::class, 'index'])
            ->name('barang.index');

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
