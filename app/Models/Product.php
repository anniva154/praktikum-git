<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'description',
        'cat_id',
        'child_cat_id',
        'price',
        'brand_id',
        'discount',
        'status',
        'photo',
        'size',
        'stock',
        'is_featured',
        'condition',
    ];

    /**
     * Generate a unique slug for the product.
     */
    public static function generateUniqueSlug($title)
    {
        $slug = Str::slug($title); // Generate base slug
        $count = Product::where('slug', 'LIKE', "{$slug}%")->count(); // Count similar slugs
        return $count ? "{$slug}-" . time() : $slug; // Append timestamp if duplicate exists
    }

    /**
     * Relationship to main category.
     */
    
     public function cat_info()
    {
        return $this->hasOne('App\Models\Category', 'id', 'cat_id');
    }

    /**
     * Relationship to subcategory.
     */
    public function sub_cat_info()
    {
        return $this->hasOne('App\Models\Category', 'id', 'child_cat_id');
    }

   
    /**
     * Related products in the same category.
     */
    public function rel_prods()
    {
        return $this->hasMany(Product::class, 'cat_id', 'cat_id')
            ->where('status', 'active')
            ->orderBy('id', 'DESC')
            ->limit(8);
    }

    /**
     * Reviews for the product.
     */
    public function getReview()
    {
        return $this->hasMany('App\Models\ProductReview', 'product_id', 'id')
            ->with('user_info')
            ->where('status', 'active')
            ->orderBy('id', 'DESC');
    }

    /**
     * Cart items associated with the product.
     */
    public function carts()
    {
        return $this->hasMany('App\Models\Cart', 'product_id', 'id')->whereNotNull('order_id');
    }

    /**
     * Wishlist items associated with the product.
     */
    public function wishlists()
    {
        return $this->hasMany('App\Models\Wishlist', 'product_id', 'id')->whereNotNull('cart_id');
    }

    /**
     * Transaction details associated with the product.
     */
    public function transactionDetails()
    {
        return $this->hasMany('App\Models\TransactionDetail', 'product_id', 'id');
    }

    /**
     * Fetch all products with category and subcategory info.
     */
    public static function getAllProduct()
    {
        return Product::with(['cat_info', 'sub_cat_info'])
            ->orderBy('id', 'desc')
            ->paginate(10);
    }

    /**
     * Get a product by its slug.
     */
    public static function getProductBySlug($slug)
    {
        return Product::with(['cat_info', 'rel_prods', 'getReview'])
            ->where('slug', $slug)
            ->first();
    }

    /**
     * Count active products.
     */
    public static function countActiveProduct()
    {
        return Product::where('status', 'active')->count();
    }
}
