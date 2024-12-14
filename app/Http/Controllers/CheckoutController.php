<?php

    namespace App\Http\Controllers;
    
    use Illuminate\Http\Request;
    use App\Models\Cart;
    use App\Models\Transaction;
    use Midtrans\Snap;
    use Midtrans\Config;
    use App\Services\RajaOngkirService;
    use App\Http\Controllers\Api\RajaOngkirController;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\DB;
    
    class CheckoutController extends Controller
    {
        private $rajaOngkirService;
    
        public function __construct(RajaOngkirService $rajaOngkirService)
        {
            $this->rajaOngkirService = $rajaOngkirService;
        }
    
        /**
         * Hitung ongkos kirim berdasarkan berat dan kurir.
         */
        public function calculateShipping(Request $request)
        {
            $request->validate([
                'destination' => 'required|string',
                'courier' => 'required|string',
                'weight' => 'required|integer|min:1',
            ]);
    
            try {
                $origin = config('rajaongkir.origin_city');
                $destination = $request->input('destination');
                $courier = $request->input('courier');
                $weight = $request->input('weight');
    
                $shippingCost = $this->rajaOngkirService->getShippingCost($origin, $destination, $weight, $courier);
    
                return response()->json($shippingCost);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
    
        /**
         * Hitung berat total keranjang berdasarkan user ID.
         */
        private function calculateCartWeight($userId)
        {
            $cartItems = Cart::where('user_id', $userId)->with('product')->get();
            return $cartItems->sum(function ($item) {
                return $item->product->weight * $item->quantity;
            });
        }
    
        /**
         * Menampilkan halaman checkout.
         */
        public function index(Request $request)
        {
            // Ambil data diskon dan produk terpilih dari request
            $discountPrice = $request->input('discount_price', 0);
            $selectedProducts = json_decode($request->input('selected_products'), true);
        
            // Validasi jika tidak ada produk yang dipilih
            if (empty($selectedProducts)) {
                return redirect()->route('cart.index')->with('error', 'Tidak ada produk yang dipilih untuk checkout.');
            }
        
            // Hitung subtotal dan kumpulkan produk yang dipilih
            $subtotal = 0;
            $selectedItems = [];
        
            foreach ($selectedProducts as $product) {
                $cartItem = \App\Models\Cart::find($product['id']);
        
                if ($cartItem) {
                    $subtotal += $product['total_price'];
                    $selectedItems[] = [
                        'id' => $cartItem->id,
                        'title' => $cartItem->product->title,
                        'quantity' => $product['quantity'],
                        'total_price' => $product['total_price'],
                    ];
                }
            }
        
            // Ambil daftar provinsi dari API RajaOngkir
            $provincesResponse = app(RajaOngkirController::class)->provinces();
            $provinces = $provincesResponse->getData();
        
            // Ambil kota berdasarkan setiap provinsi
            $citiesByProvince = [];
            foreach ($provinces as $province) {
                $citiesResponse = app(RajaOngkirController::class)->cities($province->province_id);
                $citiesByProvince[$province->province_id] = $citiesResponse->getData();
            }
        
            // Ambil provinsi dan kota asal dari .env
            $originProvince = env('ORIGIN_PROVINCE', 'Default Province'); // Default jika tidak ada
            $originCity = env('ORIGIN_CITY', 'Default City'); // Default jika tidak ada
        
            // Kirim data ke view checkout
            return view('frontend.pages.checkout', [
                'provinces' => $provinces,
                'citiesByProvince' => $citiesByProvince,  // Mengirimkan data kota berdasarkan provinsi
                'originProvince' => $originProvince,
                'originCity' => $originCity,
                'cartItems' => $selectedItems,
                'subtotal' => $subtotal,
                'discount_price' => $discountPrice,
            ]);
        }
        

    /**
     * Proses checkout.
     */
    public function checkout(Request $request)
    {
        $checkoutdata = $request->input('checkoutData');
    
        if (is_string($checkoutdata)) {
            $checkoutdata = json_decode($checkoutdata, true);
        }
    
        if (!$checkoutdata || !isset($checkoutdata['order_id'], $checkoutdata['payment_type'], $checkoutdata['gross_amount'])) {
            return response()->json(['error' => 'Data transaksi tidak lengkap atau format tidak valid.'], 400);
        }
    
        DB::beginTransaction();
        try {
            $transaction = Transaction::create([
                'order_id' => $checkoutdata['order_id'],
                'customer_name' => auth()->user()->name,
                'gross_amount' => $checkoutdata['gross_amount'],
                'payment_type' => $checkoutdata['payment_type'],
                'payment_method' => $checkoutdata['va_numbers'][0]['bank'] ?? 'N/A',
                'courier' => $request->input('courier', 'N/A'),
                'courier_service' => $request->input('courier_service', 'N/A'),
                'transaction_status' => $checkoutdata['transaction_status'],
                'transaction_time' => $checkoutdata['transaction_time'],
            ]);
    
            DB::commit();
            return response()->json(['message' => 'Transaksi berhasil diproses.', 'transaction' => $transaction]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat memproses transaksi.'], 500);
        }
    }
    

    /**
     * Dapatkan Snap Token untuk transaksi.
     */
    public function getSnapToken(Request $request)
    {
        // Validasi data yang dikirim
        $request->validate([
            'total_cost' => 'required|numeric',
            'cart_items' => 'required|array',
            'cart_items.*.id' => 'required|integer',
            'cart_items.*.title' => 'required|string',
            'cart_items.*.quantity' => 'required|integer',
            'cart_items.*.total_price' => 'required|numeric',
            'courier_service_price' => 'nullable|numeric',
            'discount_price' => 'nullable|numeric',
            'courier_name' => 'nullable|string',
        ]);

        $total_cost = $request->total_cost;

        // Menyusun detail transaksi
        $transaction_details = [
            'order_id' => uniqid('ORDER-'),
            'gross_amount' => $total_cost,
        ];

        // Menyusun detail item
        $item_details = [];
        foreach ($request->cart_items as $item) {
            $item_details[] = [
                'id' => $item['id'],
                'price' => (int) ($item['total_price'] / $item['quantity']), // Harga satuan
                'quantity' => (int) $item['quantity'],
                'name' => $item['title'],
            ];
        }

        // Tambahkan ongkir jika ada
        if ($request->has('courier_service_price') && $request->courier_service_price > 0) {
            $item_details[] = [
                'id' => 'shipping',
                'name' => 'Shipping Cost',
                'price' => (int) $request->courier_service_price,
                'quantity' => 1
            ];
        }

        // Menambahkan diskon jika ada
        if ($request->has('discount_price') && $request->discount_price > 0) {
            $item_details[] = [
                'id' => 'discount',
                'name' => 'Discount',
                'price' => -(int) $request->discount_price,
                'quantity' => 1
            ];
        }

        // Menyusun parameter untuk Snap
        $params = [
            'transaction_details' => $transaction_details,
            'item_details' => $item_details,
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => auth()->user()->phone,
            ],
        ];

        // Mendapatkan Snap Token dari Midtrans
        try {
            $snapToken = Snap::getSnapToken($params);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Token Error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mendapatkan token transaksi.'], 500);
        }
    }
}
