<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Frontend\CategoryController as FrontendCategoryController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;

/*
|-------------------------------------------------------------------------- 
| Web Routes 
|-------------------------------------------------------------------------- 
*/

// Auth routes
Auth::routes(); // Pendaftaran pengguna dinonaktifkan

// Custom user login & logout
Route::get('user/login', [FrontendController::class, 'login'])->name('login.form');
Route::post('user/login', [FrontendController::class, 'loginSubmit'])->name('login.submit');
Route::post('user/logout', [FrontendController::class, 'logout'])->name('logout');

// Custom user registration (hapus jika tidak digunakan)
Route::get('user/register', [FrontendController::class, 'register'])->name('register.form');
Route::post('user/register', [FrontendController::class, 'registerSubmit'])->name('register.submit');

// Reset password
Route::post('password-reset', [FrontendController::class, 'showResetForm'])->name('password.reset');

// Socialite authentication
Route::get('login/{provider}/', 'Auth\LoginController@redirect')->name('login.redirect');
Route::get('login/{provider}/callback/', 'Auth\LoginController@callback')->name('login.callback');

// Frontend home page
Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/home', [FrontendController::class, 'index']);
Route::get('/categories', [FrontendCategoryController::class, 'index'])->name('all-categories');
// Frontend pages
Route::get('/about-us', [FrontendController::class, 'aboutUs'])->name('about-us');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::post('/contact/message', [MessageController::class, 'store'])->name('contact.store');

// Product-related routes
Route::get('product-detail/{slug}', [FrontendController::class, 'productDetail'])->name('product-detail');
Route::post('/product/search', [FrontendController::class, 'productSearch'])->name('product.search');
Route::get('/product-cat/{slug}', [FrontendController::class, 'productCat'])->name('product-cat');
Route::get('/product-sub-cat/{slug}/{sub_slug}', [FrontendController::class, 'productSubCat'])->name('product-sub-cat');
Route::get('/product-brand/{slug}', [FrontendController::class, 'productBrand'])->name('product-brand');

// Cart-related routes
Route::get('/add-to-cart/{slug}', [CartController::class, 'addToCart'])->name('add-to-cart')->middleware('user');
Route::post('/add-to-cart', [CartController::class, 'singleAddToCart'])->name('single-add-to-cart')->middleware('user');
Route::get('cart-delete/{id}', [CartController::class, 'cartDelete'])->name('cart-delete');
Route::post('cart-update', [CartController::class, 'cartUpdate'])->name('cart.update');
Route::get('/cart', function () {
    return view('frontend.pages.cart');
})->name('cart');

Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout')->middleware('user');

// Wishlist
Route::get('/wishlist', function () {
    return view('frontend.pages.wishlist');
})->name('wishlist');
Route::get('/wishlist/{slug}', [WishlistController::class, 'wishlist'])->name('add-to-wishlist')->middleware('user');
Route::get('wishlist-delete/{id}', [WishlistController::class, 'wishlistDelete'])->name('wishlist-delete');

// Orders
Route::post('cart/order', [OrderController::class, 'store'])->name('cart.order');
// Define route for invoice viewing
Route::get('invoice/{order_number}', [OrderController::class, 'invoice'])->name('invoice');

Route::get('/income', [OrderController::class, 'incomeChart'])->name('product.order.income');

// Product listing and filtering
Route::get('/product-grids', [FrontendController::class, 'productGrids'])->name('product-grids');
Route::get('/product-lists', [FrontendController::class, 'productLists'])->name('product-lists');
Route::match(['get', 'post'], '/filter', [FrontendController::class, 'productFilter'])->name('shop.filter');

// Order tracking
Route::get('/product/track', [OrderController::class, 'orderTrack'])->name('order.track');
Route::post('product/track/order', [OrderController::class, 'productTrackOrder'])->name('product.track.order');

// Newsletter
Route::post('/subscribe', [FrontendController::class, 'subscribe'])->name('subscribe');

// Product reviews
Route::resource('/review', ProductReviewController::class);
Route::post('product/{slug}/review', [ProductReviewController::class, 'store'])->name('review.store');

// Post comments
Route::post('post/{slug}/comment', [PostCommentController::class, 'store'])->name('post-comment.store');
Route::resource('/comment', PostCommentController::class);

// Coupons
Route::post('/coupon-store', [CouponController::class, 'couponStore'])->name('coupon-store');

// Payment routes
Route::get('payment', [PayPalController::class, 'payment'])->name('payment');
Route::get('cancel', [PayPalController::class, 'cancel'])->name('payment.cancel');
Route::get('payment/success', [PayPalController::class, 'success'])->name('payment.success');

// Admin section
Route::group(['prefix' => '/admin', 'middleware' => ['auth', 'admin']], function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');
    Route::get('/file-manager', function () {
        return view('backend.layouts.file-manager');
    })->name('file-manager');
    Route::resource('users', 'UsersController');
    Route::resource('banner', 'BannerController');
    Route::resource('brand', 'BrandController');
    Route::get('/profile', [AdminController::class, 'profile'])->name('admin-profile');
    Route::post('/profile/{id}', [AdminController::class, 'profileUpdate'])->name('profile-update');
    Route::resource('/category', 'CategoryController');
    Route::resource('/product', 'ProductController');
    Route::post('/category/{id}/child', [CategoryController::class, 'getChildByParent']);
    Route::resource('/post-category', 'PostCategoryController');
    Route::resource('/post-tag', 'PostTagController');
    Route::resource('/post', 'PostController');
    Route::resource('/message', 'MessageController');
    Route::get('/message/five', [MessageController::class, 'messageFive'])->name('messages.five');
    Route::resource('/order', 'OrderController');
    Route::resource('/shipping', 'ShippingController');
    Route::resource('/coupon', 'CouponController');
    Route::get('settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('setting/update', [AdminController::class, 'settingsUpdate'])->name('settings.update');
    Route::get('/notification/{id}', [NotificationController::class, 'show'])->name('admin.notification');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('all.notification');
    Route::delete('/notification/{id}', [NotificationController::class, 'delete'])->name('notification.delete');
    Route::get('change-password', [AdminController::class, 'changePassword'])->name('change.password.form');
    Route::post('change-password', [AdminController::class, 'changPasswordStore'])->name('change.password');
});

// User section
Route::group(['prefix' => '/user', 'middleware' => ['user']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('user');
    Route::get('/profile', [HomeController::class, 'profile'])->name('user-profile');
    Route::post('/profile/{id}', [HomeController::class, 'profileUpdate'])->name('user-profile-update');
    // Ubah rute order menjadi transaction
    Route::get('/transaction', [TransactionsController::class, 'index'])->name('user.transaction-history');
    Route::get('/transaction/show/{id}', [TransactionsController::class, 'show'])->name('user.transaction.show');
    Route::delete('/transaction/delete/{id}', [TransactionsController::class, 'destroy'])->name('user.transaction.delete');
    // Lainnya tetap
    Route::get('/user-review', [HomeController::class, 'productReviewIndex'])->name('user.productreview.index');
    Route::delete('/user-review/delete/{id}', [HomeController::class, 'productReviewDelete'])->name('user.productreview.delete');
    Route::get('/user-review/edit/{id}', [HomeController::class, 'productReviewEdit'])->name('user.productreview.edit');
    Route::patch('/user-review/update/{id}', [HomeController::class, 'productReviewUpdate'])->name('user.productreview.update');
    Route::get('user-post/comment', [HomeController::class, 'userComment'])->name('user.post-comment.index');
    Route::delete('user-post/comment/delete/{id}', [HomeController::class, 'userCommentDelete'])->name('user.post-comment.delete');
    Route::get('user-post/comment/edit/{id}', [HomeController::class, 'userCommentEdit'])->name('user.post-comment.edit');
    Route::patch('user-post/comment/update/{id}', [HomeController::class, 'userCommentUpdate'])->name('user.post-comment.update');
    Route::get('change-password', [HomeController::class, 'changePassword'])->name('user.change.password.form');
    Route::post('change-password', [HomeController::class, 'changPasswordStore'])->name('change.password');
});

Route::prefix('user/address')->name('address.')->middleware('auth')->group(function () {
    Route::get('/', [AddressController::class, 'index'])->name('index');
    Route::get('/create', [AddressController::class, 'create'])->name('create');
    Route::post('/', [AddressController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [AddressController::class, 'edit'])->name('edit');
    Route::put('/{id}', [AddressController::class, 'update'])->name('update');
    Route::delete('/{id}', [AddressController::class, 'destroy'])->name('destroy');
});

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});


// Checkout Routes
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/calculate-shipping', [CheckoutController::class, 'calculateShipping'])->name('calculate.shipping');
    Route::post('/get-snap-token', [CheckoutController::class, 'getSnapToken'])->name('checkout.snapToken');
    Route::post('/calculate-cart-weight', [CheckoutController::class, 'calculateCartWeight']);
    Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');
    Route::post('/midtrans/callback', [CheckoutController::class, 'midtransCallback'])->name('checkout.midtransCallback');
    Route::post('/checkout/update-payment-status', [CheckoutController::class, 'updatePaymentStatus'])->name('checkout.updatePaymentStatus');
    Route::get('/midtrans/redirect/{token}', [CheckoutController::class, 'redirect'])->name('midtrans.redirect');
    Route::post('/payment', [CheckoutController::class, 'checkout'])->name('Payment');
   

});

use App\Http\Controllers\InvoiceController;

Route::get('/invoice/{order_id}', [InvoiceController::class, 'show'])->name('invoice.show');


Route::get('user/transaction-history', [TransactionsController::class, 'index'])->name('user.transaction-history');