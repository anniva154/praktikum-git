<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RajaOngkirController;
use App\Http\Controllers\Api\MidtransController;
use App\Http\Controllers\Api\CheckoutController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/midtrans/callback', [MidtransController::class, 'callback']);


Route::get('/provinces', [RajaOngkirController::class, 'provinces']);
Route::get('/cities/{provinceId}', [RajaOngkirController::class, 'cities']);
Route::post('cek-ongkir', [RajaOngkirController::class, 'cekOngkir']);
Route::post('/api/cost', [RajaOngkirController::class, 'getShippingCost'])->name('get.shipping.services');

