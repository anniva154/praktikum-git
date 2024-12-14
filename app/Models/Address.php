<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'street',
        'city',
        'province',
        'postal_code',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function orders()
{
    return $this->hasMany(Order::class, 'address_id');
}

 
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}

