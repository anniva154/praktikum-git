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

        $laboratorium = Laboratorium::query();

        if (Auth::check()) {
            $role = Auth::user()->role;

            // ADMIN & PIMPINAN → SEMUA LAB
            if (in_array($role, ['admin', 'pimpinan'])) {
                // tidak difilter
            }
            // KAPROLI & PENGGUNA → SESUAI JURUSAN
            elseif (in_array($role, ['kaproli', 'pengguna'])) {
                $laboratorium->where(
                    'id_jurusan',
                    Auth::user()->id_jurusan
                );
            }
        }

        $view->with('laboratorium', $laboratorium->get());
    });
}
}