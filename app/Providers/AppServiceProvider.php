<?php

namespace App\Providers;

use App\Models\Laboratorium;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {

            // default: semua lab (untuk admin)
            $laboratorium = Laboratorium::query();

            // jika login & role kaproli → filter jurusan
            if (Auth::check() && Auth::user()->role === 'kaproli') {
                $laboratorium->where('id_jurusan', Auth::user()->id_jurusan);
            }

            $view->with('laboratorium', $laboratorium->get());
        });
    }
}
