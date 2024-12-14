<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        $order = Order::where('order_number', $request->order_id)->first();

        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
        }

        // Update status pembayaran berdasarkan callback Midtrans
        if ($request->transaction_status == 'settlement') {
            $order->payment_status = 'paid';
            $order->order_status = 'processed';
        } elseif ($request->transaction_status == 'pending') {
            $order->payment_status = 'pending';
        } else {
            $order->payment_status = 'failed';
        }

        $order->payment_method = $request->payment_type; // Simpan metode pembayaran
        $order->save();

        return response()->json(['status' => 'success']);
    }

    private function getPaymentMethod($callback): string
    {
        if ($callback->payment_type == 'credit_card') {
            return 'Credit Card';
        } else if ($callback->payment_type == 'bank_transfer') {
            return $callback->va_numbers[0]->bank;
        } else if ($callback->payment_type == 'echannel') {
            return 'Mandiri';
        } else if ($callback->payment_type == 'qris') {
            return $callback->acquirer;
        } else if ($callback->payment_type == 'cstore') {
            return $callback->store;
        } else {
            return 'Unknown';
        }
    }
}
