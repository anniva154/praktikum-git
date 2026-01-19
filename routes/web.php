<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Backend\DashboardAdminController;
use App\Http\Controllers\Backend\PenggunaAdminController;
use App\Http\Controllers\Backend\JurusanController;
use App\Http\Controllers\Backend\LabController;
use App\Http\Controllers\Backend\JadwalLabController;
use App\Http\Controllers\Backend\BarangLabController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTE
|--------------------------------------------------------------------------
*/
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

        // Master Data
        Route::resource('pengguna', PenggunaAdminController::class);
        Route::resource('jurusan', JurusanController::class)->only(['index']);
        Route::resource('lab', LabController::class);

        // ===============================
        // Jadwal & Barang per Laboratorium
        // ===============================

        // Jadwal Lab
        Route::get('jadwal-lab/{id_lab}', [JadwalLabController::class, 'index'])
            ->name('jadwal.lab');

        // 🔥 LIHAT BARANG PER LAB
        Route::get('barang-lab/{lab}', [BarangLabController::class, 'index'])
            ->name('barang.lab');

        // ✏️ EDIT STATUS BARANG (ADMIN)
        Route::get('barang-lab/{lab}/{barang}/edit', [BarangLabController::class, 'edit'])
            ->name('barang.edit');

        Route::put('barang-lab/{lab}/{barang}', [BarangLabController::class, 'update'])
            ->name('barang.update');

        // 🗑️ HAPUS BARANG (ADMIN)
        Route::delete('barang-lab/{lab}/{barang}', [BarangLabController::class, 'destroy'])
            ->name('barang.destroy');

    });


Route::prefix('kaproli')
    ->name('kaproli.')
    ->middleware(['auth', 'role:kaproli'])
    ->group(function () {

        Route::view('/dashboard', 'backend.kaproli.dashboard')
            ->name('dashboard');

        // contoh jika kaproli hanya lihat data
        Route::resource('jurusan', JurusanController::class)->only(['index', 'show']);
        Route::resource('lab', LabController::class)->only(['index', 'show']);
    // =====================
        // DATA BARANG (PER LAB)
        // =====================
        Route::prefix('lab/{lab}')
            ->group(function () {
                Route::resource('barang', BarangLabController::class);
            });
        });
        
Route::prefix('pimpinan')
    ->name('pimpinan.')
    ->middleware(['auth', 'role:pimpinan'])
    ->group(function () {

        Route::view('/dashboard', 'backend.pimpinan.dashboard')
            ->name('dashboard');

        // contoh jika kaproli hanya lihat data
        Route::resource('jurusan', JurusanController::class)->only(['index', 'show']);
        Route::resource('lab', LabController::class)->only(['index', 'show']);
    
        });
/*
|--------------------------------------------------------------------------
| PUBLIC ROUTE
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
