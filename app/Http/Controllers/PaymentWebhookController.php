<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\TransactionHistory;

class PaymentWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        $order_id = $payload['order_id']; // Ambil order_id dari notifikasi
        $transaction_status = $payload['transaction_status'];

        $order = Order::where('midtrans_order_id', $order_id)->first();

        if ($order) {
            // Update status pembayaran
            if ($transaction_status === 'settlement') {
                $order->payment_status = 'paid';
                $order->status = 'process';
                $order->save();

                // Catat histori transaksi
                TransactionHistory::create([
                    'order_id' => $order->id,
                    'status' => 'paid',
                    'description' => 'Pembayaran berhasil melalui Midtrans',
                ]);

                // Generate invoice
                $this->generateInvoice($order);
            }
        }
    }

    private function generateInvoice($order)
    {
        // Implementasi generate invoice
    }
}
