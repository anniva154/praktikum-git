<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function show($orderId)
    {
        $order = Order::where('order_number', $orderId)
            ->with('user', 'address', 'details', 'shipping')
            ->firstOrFail();

        // Jika Anda ingin menampilkan invoice di halaman
        return view('frontend.pages.invoice', compact('order'));
    }
}
