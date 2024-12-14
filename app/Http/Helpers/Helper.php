<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\Category;
use App\Models\PostTag;
use App\Models\PostCategory;
use App\Models\Order;
use App\Models\Wishlist;
use App\Models\Shipping;
use App\Models\Cart;

class Helper
{
    // Fetch unread messages
    public static function messageList()
    {
        return Message::whereNull('read_at')->orderBy('created_at', 'desc')->get();
    }

    // Fetch all parent categories with their children
    public static function getAllCategory()
    {
        $category = new Category();
        return $category->getAllParentWithChild();
    }

    // Render header categories menu
    public static function getHeaderCategory()
    {
        $menu = self::getAllCategory();

        // Validasi bahwa $menu adalah collection/array
        if (!is_iterable($menu)) {
            return; // Jika bukan array atau collection, hentikan eksekusi
        }

        if ($menu->isNotEmpty()) {
            ?>
            <li>
                <a href="javascript:void(0);">Category<i class="ti-angle-down"></i></a>
                <ul class="dropdown border-0 shadow">
                    <?php
                    foreach ($menu as $cat_info) {
                        if ($cat_info->child_cat->count() > 0) {
                            ?>
                            <li>
                                <a href="<?php echo route('product-cat', $cat_info->slug); ?>">
                                    <?php echo $cat_info->title; ?>
                                </a>
                                <ul class="dropdown sub-dropdown border-0 shadow">
                                    <?php
                                    foreach ($cat_info->child_cat as $sub_menu) {
                                        ?>
                                        <li>
                                            <a href="<?php echo route('product-sub-cat', [$cat_info->slug, $sub_menu->slug]); ?>">
                                                <?php echo $sub_menu->title; ?>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </li>
                            <?php
                        } else {
                            ?>
                            <li>
                                <a href="<?php echo route('product-cat', $cat_info->slug); ?>">
                                    <?php echo $cat_info->title; ?>
                                </a>
                            </li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </li>
            <?php
        }
    }

    // Fetch product categories
    public static function productCategoryList($option = 'all')
    {
        if ($option == 'all') {
            return Category::orderBy('id', 'DESC')->get();
        }
        return Category::has('products')->orderBy('id', 'DESC')->get();
    }

    // Fetch post tags
    public static function postTagList($option = 'all')
    {
        if ($option == 'all') {
            return PostTag::orderBy('id', 'desc')->get();
        }
        return PostTag::has('posts')->orderBy('id', 'desc')->get();
    }

    // Fetch post categories
    public static function postCategoryList($option = "all")
    {
        if ($option == 'all') {
            return PostCategory::orderBy('id', 'DESC')->get();
        }
        return PostCategory::has('posts')->orderBy('id', 'DESC')->get();
    }

    // Cart count for a user
    public static function cartCount($user_id = '')
    {
        if (Auth::check()) {
            $user_id = $user_id ?: auth()->user()->id;
            return Cart::where('user_id', $user_id)->whereNull('order_id')->sum('quantity');
        }
        return 0;
    }

    // Relationship cart with product
    public function product()
    {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }

    // Get all products from the cart
    public static function getAllProductFromCart($user_id = '')
    {
        if (Auth::check()) {
            $user_id = $user_id ?: auth()->user()->id;
            return Cart::with('product')->where('user_id', $user_id)->whereNull('order_id')->get();
        }
        return collect(); // Kembalikan collection kosong jika tidak ada user
    }

    // Total cart price
    public static function totalCartPrice($user_id = '')
    {
        if (Auth::check()) {
            $user_id = $user_id ?: auth()->user()->id;
            return Cart::where('user_id', $user_id)->whereNull('order_id')->sum('price');
        }
        return 0;
    }

    // Wishlist count for a user
    public static function wishlistCount($user_id = '')
    {
        if (Auth::check()) {
            $user_id = $user_id ?: auth()->user()->id;
            return Wishlist::where('user_id', $user_id)->whereNull('cart_id')->sum('quantity');
        }
        return 0;
    }

    // Get all products from wishlist
    public static function getAllProductFromWishlist($user_id = '')
    {
        if (Auth::check()) {
            $user_id = $user_id ?: auth()->user()->id;
            return Wishlist::with('product')->where('user_id', $user_id)->whereNull('cart_id')->get();
        }
        return collect(); // Kembalikan collection kosong jika tidak ada user
    }

    // Total wishlist price
    public static function totalWishlistPrice($user_id = '')
    {
        if (Auth::check()) {
            $user_id = $user_id ?: auth()->user()->id;
            return Wishlist::where('user_id', $user_id)->whereNull('cart_id')->sum('price');
        }
        return 0;
    }

    // Total price with shipping and coupon
    public static function grandPrice($id, $user_id)
    {
        $order = Order::find($id);
        if ($order) {
            $shipping_price = (float)$order->shipping->price;
            $order_price = self::totalCartPrice($user_id);
            return number_format((float)($order_price + $shipping_price), 2, '.', '');
        }
        return 0;
    }

    // Admin earnings per month
    public static function earningPerMonth()
    {
        $month_data = Order::where('status', 'delivered')->get();
        $price = 0;

        foreach ($month_data as $data) {
            $price += $data->cart_info->sum('price');
        }

        return number_format((float)$price, 2, '.', '');
    }

    // Fetch all shipping data
    public static function shipping()
    {
        return Shipping::orderBy('id', 'DESC')->get();
    }
}

