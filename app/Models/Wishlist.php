<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table = 'wishlists';

    protected $fillable = [
        'user_id', 
        'product_id', 
        'cart_id', 
        'price', 
        'amount', 
        'quantity'
    ];

    // Relasi ke tabel products
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
