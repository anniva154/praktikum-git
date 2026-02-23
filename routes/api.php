<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\PeminjamanController;

// Route untuk menangkap data dari Fonnte
Route::post('/webhook/fonnte', [PeminjamanController::class, 'webhookFonnte']);