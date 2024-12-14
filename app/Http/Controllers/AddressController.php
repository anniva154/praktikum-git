<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Api\RajaOngkirController; // Tambahkan import RajaOngkirController
use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
class AddressController extends Controller
{
    
    public function index()
    {
        // Mengambil semua alamat yang dimiliki oleh pengguna
        $addresses = Address::all(); // Anda bisa menambahkan `auth()->user()` jika ingin mengambil alamat untuk pengguna tertentu

        // Menampilkan halaman daftar alamat
        return view('frontend.user.address.index', compact('addresses'));
    }
}
