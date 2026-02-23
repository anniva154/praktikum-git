<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Artisan::command('logs:clear', function () {
    File::put(storage_path('logs/laravel.log'), '');
    $this->info('Log berhasil dibersihkan!');
})->purpose('Membersihkan file log Laravel')->daily(); // Otomatis bersih tiap minggu