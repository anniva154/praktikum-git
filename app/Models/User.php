<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'photo', 
        'role', 
        'provider', 
        'provider_id', 
        'status'
    ];

    // Relasi ke wishlist
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class, 'user_id');
    }

    // Relasi ke cart (jika ada tabel cart)
    public function cart()
    {
        return $this->hasMany(Cart::class, 'user_id');
    }
    // Add this method in the User model
public function isAdmin()
{
    return $this->role === 'admin';
}
public function addresses()
{
    return $this->hasMany(Address::class);
}

public function primaryAddress()
{
    return $this->hasOne(Address::class)->where('is_primary', true);
}

}
