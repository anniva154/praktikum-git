<?php

namespace App\Providers;

use App\Models\Barang;
use App\Models\User;
use App\Models\Laboratorium;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        /**
         * =====================================
         * SHARE DATA LABORATORIUM (GLOBAL)
         * =====================================
         */
        View::composer('*', function ($view) {
            if (!Auth::check()) {
                return;
            }

            $user = Auth::user();

            $laboratorium = Laboratorium::query();

            // ADMIN & PIMPINAN → semua lab
            if (in_array($user->role, ['admin', 'pimpinan'])) {
                // no filter
            }
            // KAPROLI & PENGGUNA → sesuai jurusan
            elseif (in_array($user->role, ['kaproli', 'pengguna'])) {
                $laboratorium->where('id_jurusan', $user->id_jurusan);
            }

            $view->with('laboratorium', $laboratorium->get());
        });
Paginator::useBootstrapFour();
    }
    
}
