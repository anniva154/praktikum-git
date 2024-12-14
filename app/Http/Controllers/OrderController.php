<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Shipping;
use App\Models\Address; // Mengambil data alamat
use App\Models\TransactionHistory; // Model untuk transaksi history
use App\Models\User;
use Notification;
use Helper;
use Illuminate\Support\Str;
use App\Notifications\StatusNotification;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification as MidtransNotification;

class OrderController extends Controller
{
    // Setup Midtrans
    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false); // Set false for sandbox
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Display a listing of the resource (Admin Backend).
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::orderBy('id', 'DESC')->paginate(10);
        return view('backend.order.index', compact('orders')); // Mengirimkan data orders ke view
    }

    /**
     * Show the form for creating a new resource (Admin Backend).
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Jika admin membutuhkan fitur untuk membuat order baru
    }

    /**
     * Store a newly created resource in storage (Backend Admin - Store Order).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
      
    /**
     * Handle Midtrans Callback
     */
    public function handleMidtransCallback(Request $request)
    {
        $notification = new MidtransNotification();
        $transaction_status = $notification->transaction_status;
        $order_id = $notification->order_id;

        $order = Order::where('order_number', $order_id)->first();
        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
        }

        switch ($transaction_status) {
            case 'settlement':
                $order->payment_status = 'paid';
                $order->transaction_status = 'success';
                $order->save();

                // Record transaction history
                $this->createTransactionHistory($order, 'Payment Successful');
                break;

            case 'pending':
                $order->payment_status = 'unpaid';
                $order->transaction_status = 'pending';
                break;

            case 'cancel':
            case 'deny':
            case 'expire':
                $order->payment_status = 'unpaid';
                $order->transaction_status = 'failed';
                break;
        }

        $order->save();
        return response()->json(['status' => 'success']);
    }

    /**
     * Create Transaction History
     */
    public function createTransactionHistory(Order $order, $status)
    {
        TransactionHistory::create([
            'order_id' => $order->id,
            'status' => $status,
            'description' => 'Payment status changed to ' . $status,
        ]);
    }
// Add to your OrderController
public function paymentSuccess(Request $request, $order_number)
{
    // Find the order by order_number
    $order = Order::where('order_number', $order_number)->first();

    if (!$order) {
        return redirect()->route('home')->with('error', 'Order not found.');
    }

    // Update order payment status and other relevant details
    $order->payment_status = 'paid'; // or any other logic based on payment success
    $order->save();

    // Optionally, save transaction history in another table
    // Transaction::create([
    //     'order_id' => $order->id,
    //     'amount' => $order->total_amount,
    //     'payment_method' => 'mitrands',
    //     'transaction_id' => $request->transaction_id, // assuming Mitrands returns this
    //     'status' => 'success',
    // ]);

    // Redirect to the invoice page
    return redirect()->route('invoice', ['order_number' => $order->order_number]);
}
public function invoice($order_number)
    {
        // Retrieve the order by its order_number
        $order = Order::where('order_number', $order_number)->first();

        // If the order does not exist, return a 404 error page
        if (!$order) {
            
       
abort(404, 'Order not found');
        }

        // Assuming you have an Invoice related to the order
        $invoice = Invoice::where('order_id', $order->id)->first();

        // If there's no invoice associated with the order, you may handle it here
        if (!$invoice) {
            abort(404, 'Invoice not found');
        }

        
   
// Pass the order and invoice to the view
        return view('frontend.invoice', compact('order', 'invoice'));
    }
    public function store(Request $request)
    {
        // Validasi input
        $this->validate($request, [
            'address_id' => 'required|exists:addresses,id',
            'coupon' => 'nullable|numeric',
            'phone' => 'numeric|required',
            'email' => 'string|required'
        ]);

        // Validasi jika keranjang kosong
        if (empty(Cart::where('user_id', auth()->user()->id)->where('order_id', null)->first())) {
            return redirect()->back()->with('error', 'Cart is empty!');
        }

        // Ambil alamat dan informasi pengguna
        $address = Address::find($request->address_id);
        $user = auth()->user();

        // Simpan transaksi utama
        $transaction = new Transaction();
        $transaction_data = [
            'order_id' => 'ORD-' . strtoupper(Str::random(10)), // order_id unik
            'user_id' => $user->id,
            'total_amount' => $this->calculateTotalAmount(), // Buat fungsi untuk menghitung total
            'status' => 'pending',
            'payment_method' => $request->payment_method,
            'payment_status' => 'unpaid',
            'shipping_address' => $address->address, // Misalnya alamat dikirim ke transaksi
        ];
        $transaction->fill($transaction_data);
        $transaction->save();

        // Menyimpan detail transaksi
        foreach ($request->cart_items as $item) {
            $transaction_detail = new TransactionDetail([
                'transaction_id' => $transaction->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['total_price'],
                'total' => $item['quantity'] * $item['total_price'],
            ]);
            $transaction_detail->save();
        }

        // Pengaturan pembayaran dengan Midtrans
        $snapToken = $this->createMidtransPayment($transaction);

        // Jika pembayaran berhasil menggunakan Midtrans, lanjutkan proses
        return redirect()->route('payment', ['snap_token' => $snapToken]);
    }

    // Fungsi untuk menghitung total amount transaksi
    private function calculateTotalAmount()
    {
        // Lakukan perhitungan total amount dari cart atau transaksi
        // Misalnya menggunakan helper atau query langsung ke cart yang sudah ada
        return Cart::where('user_id', auth()->user()->id)->sum('total_price');
    }

    // Fungsi untuk mengatur pembayaran dengan Midtrans
    private function createMidtransPayment(Transaction $transaction)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $transaction->order_id,  // Pastikan order_id dari transaksi sudah benar
                'gross_amount' => $transaction->total_amount,
            ],
            'customer_details' => [
                'first_name' => $transaction->user->name,
                'email' => $transaction->user->email,
                'phone' => $transaction->user->phone,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return $snapToken;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}

    // Metode lainnya...
