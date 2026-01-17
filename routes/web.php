<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Backend\PenggunaAdminController;
use App\Http\Controllers\Backend\JurusanController;
use App\Http\Controllers\Backend\LabController;

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
    ->middleware(['auth', 'role:admin,kaproli,pimpinan'])
    ->group(function () {

        // Dashboard
        Route::view('/dashboard', 'backend.admin.dashboard')
            ->name('dashboard');

        // Master Data
        Route::resource('pengguna', PenggunaAdminController::class);
        Route::resource('jurusan', JurusanController::class)->only(['index']);
        Route::resource('lab', LabController::class);

    });
Route::prefix('kaproli')
    ->name('kaproli.')
    ->middleware(['auth', 'role:kaproli'])
    ->group(function () {

        Route::view('/dashboard', 'backend.kaproli.dashboard')
            ->name('dashboard');

        // contoh jika kaproli hanya lihat data
        Route::resource('jurusan', JurusanController::class)->only(['index','show']);
        Route::resource('lab', LabController::class)->only(['index','show']);
    });
Route::prefix('pimpinan')
    ->name('pimpinan.')
    ->middleware(['auth', 'role:pimpinan'])
    ->group(function () {

        Route::view('/dashboard', 'backend.pimpinan.dashboard')
            ->name('dashboard');

        // contoh jika kaproli hanya lihat data
        Route::resource('jurusan', JurusanController::class)->only(['index','show']);
        Route::resource('lab', LabController::class)->only(['index','show']);
    });
/*
|--------------------------------------------------------------------------
| PUBLIC ROUTE
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
