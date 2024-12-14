<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    protected $product = null;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    // Tambahkan produk ke dalam keranjang
    public function addToCart(Request $request, $slug)
    {
        // Cari produk berdasarkan slug
        $product = Product::where('slug', $slug)->first();
        
        // Jika produk tidak ditemukan, kembalikan error
        if (!$product) {
            return back()->with('error', 'Product not found.');
        }
    
        // Ambil kuantitas dari request, atau set ke 1 jika tidak ada
        $quantity = $request->input('quantity', 1);
    
        // Validasi bahwa kuantitas tidak melebihi stok produk
        if ($quantity > $product->stock) {
            return back()->with('error', 'Insufficient stock.');
        }
    
        // Cek apakah produk sudah ada di keranjang
        $cartItem = Cart::where('user_id', auth()->id())
                        ->where('product_id', $product->id)
                        ->whereNull('order_id')
                        ->first();
    
        if ($cartItem) {
            // Jika ada, tambahkan kuantitas yang baru
            $cartItem->quantity += $quantity;
            $cartItem->amount = $cartItem->quantity * $cartItem->price;
    
            // Jika stok tidak mencukupi, kembalikan error
            if ($cartItem->quantity > $product->stock) {
                return back()->with('error', 'Insufficient stock.');
            }
    
            $cartItem->save();
        } else {
            // Jika produk belum ada di keranjang, buat entri baru
            $cart = new Cart();
            $cart->user_id = auth()->id();
            $cart->product_id = $product->id;
            $cart->price = $product->price - ($product->price * $product->discount) / 100;
            $cart->quantity = $quantity;
            $cart->amount = $cart->price * $quantity;
    
            $cart->save();
        }
    
        return back()->with('success', 'Product added to cart.');
    }
    

public function singleAddToCart(Request $request){
    $request->validate([
        'slug'      =>  'required',
        'quant'      =>  'required',
    ]);
    // dd($request->quant[1]);


    $product = Product::where('slug', $request->slug)->first();
    if($product->stock <$request->quant[1]){
        return back()->with('error','Out of stock, You can add other products.');
    }
    if ( ($request->quant[1] < 1) || empty($product) ) {
        request()->session()->flash('error','Invalid Products');
        return back();
    }    

    $already_cart = Cart::where('user_id', auth()->user()->id)->where('order_id',null)->where('product_id', $product->id)->first();

    // return $already_cart;

    if($already_cart) {
        $already_cart->quantity = $already_cart->quantity + $request->quant[1];
        // $already_cart->price = ($product->price * $request->quant[1]) + $already_cart->price ;
        $already_cart->amount = ($product->price * $request->quant[1])+ $already_cart->amount;

        if ($already_cart->product->stock < $already_cart->quantity || $already_cart->product->stock <= 0) return back()->with('error','Stock not sufficient!.');

        $already_cart->save();
        
    }else{
        
        $cart = new Cart;
        $cart->user_id = auth()->user()->id;
        $cart->product_id = $product->id;
        $cart->price = ($product->price-($product->price*$product->discount)/100);
        $cart->quantity = $request->quant[1];
        $cart->amount=($product->price * $request->quant[1]);
        if ($cart->product->stock < $cart->quantity || $cart->product->stock <= 0) return back()->with('error','Stock not sufficient!.');
        // return $cart;
        $cart->save();
    }
    request()->session()->flash('success','Product has been added to cart.');
    return back();       
} 


    // Dapatkan data keranjang
    private function getCartData()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', auth()->id())
            ->where('order_id', null)
            ->get();

        $subtotal = $cartItems->sum(fn ($item) => $item->amount);
        $discount_price = session('discount_price', 0);

        return compact('cartItems', 'subtotal', 'discount_price');
    }

    // Checkout
    public function checkout()
    {
        $cartData = $this->getCartData();

        session()->put('cart', $cartData['cartItems']->toArray());
        $total_amount = $cartData['subtotal'] - $cartData['discount_price'];

        return view('frontend.pages.checkout', [
            'cartData' => $cartData['cartItems'],
            'subtotal' => $cartData['subtotal'],
            'coupon' => session('coupon', null),
            'discount_price' => $cartData['discount_price'],
            'total_amount' => $total_amount,
        ]);
    }

    // Terapkan kupon
    public function applyCoupon(Request $request)
    {
        $request->validate(['coupon_code' => 'required|string']);

        $coupon = Coupon::where('code', $request->coupon_code)->first();

        if (!$coupon) {
            return back()->with('error', 'Kupon tidak valid.');
        }

        $cartData = $this->getCartData();
        $discount_price = ($coupon->discount / 100) * $cartData['subtotal'];

        session()->put('discount_price', $discount_price);

        return back()->with('success', 'Kupon berhasil diterapkan.');
    }

    // Perbarui jumlah barang di keranjang
    public function cartUpdate(Request $request)
    {
        $request->validate([
            'quant.*' => 'required|integer|min:1',
            'qty_id.*' => 'required|integer|exists:carts,id',
        ]);

        foreach ($request->qty_id as $index => $id) {
            $cart = Cart::find($id);
            if ($cart) {
                $quantity = $request->quant[$index];

                if ($cart->product->stock < $quantity) {
                    return back()->with('error', "Stok untuk {$cart->product->name} tidak mencukupi.");
                }

                $cart->quantity = $quantity;
                $cart->amount = $cart->product->price * $quantity;
                $cart->save();
            }
        }

        return back()->with('success', 'Keranjang berhasil diperbarui.');
    }

    // Hapus item dari keranjang
    public function cartDelete(Request $request)
    {
        $cart = Cart::findOrFail($request->id);
        $cart->delete();

        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    // Tampilkan halaman keranjang
    public function cart()
    {
        $cartData = $this->getCartData();
        return view('frontend.cart', $cartData);
    }
}
