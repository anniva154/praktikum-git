<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthController;
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
use App\Http\Controllers\Backend\PeminjamanController;
use App\Http\Controllers\Backend\Pengguna\PengajuanController;



Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

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

    });


Route::prefix('kaproli')
    ->name('kaproli.')
    ->middleware(['auth', 'role:kaproli'])
    ->group(function () {

        Route::view('/dashboard', 'backend.kaproli.dashboard')
            ->name('dashboard');

        Route::resource('jurusan', JurusanController::class)->only(['index', 'show']);
        Route::resource('lab', LabController::class)->only(['index', 'show']);
   
        Route::prefix('lab/{lab}')
            ->group(function () {
                Route::resource('barang', BarangLabController::class);
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

        // =======================
        // EXPORT PDF (WAJIB DI ATAS ROUTE {PARAM})
        // =======================
        Route::get(
            '/export/pdf/{tipe}/{lab?}',
            [ExportPimpinanController::class, 'exportPdf']
        )->name('export.pdf');

        // =======================
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
        // BARANG LAB
        // =======================
        Route::get('/barang-lab/{lab}', [BarangLabController::class, 'index'])
            ->name('barang.lab');

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

        // =======================
        // DASHBOARD
        // =======================
        Route::get('/dashboard', [DashboardPenggunaController::class, 'index'])
            ->name('dashboard');

        // =======================
        // JADWAL LAB
        // =======================
       
Route::get('/jadwal-lab/{lab}', [JadwalLabController::class, 'pengguna'])
            ->name('jadwal.lab');

        Route::get('/barang/{lab}', [BarangLabController::class, 'index'])
            ->name('barang.index');
        // =======================
         Route::get('/peminjaman', [PeminjamanController::class, 'index'])
            ->name('peminjaman.index');

        Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])
            ->name('peminjaman.create');

        Route::post('/peminjaman', [PeminjamanController::class, 'store'])
            ->name('peminjaman.store');
        // =======================
        // PENGAJUAN BARANG
        // =======================
        //Route::get('/pengajuan', [PengajuanController::class, 'index'])
            //->name('pengajuan');
    });

/*
|--------------------------------------------------------------------------
| Landingpage ROUTE
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
