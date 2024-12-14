<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Order extends Model
{
    use HasFactory;

    //protected $table = 'orders';
    protected $quarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function shipping()
    {
        return $this->hasOne(Shippings::class, 'order_id', 'id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    public function transactions()
    {
        return $this->hasOne(Transactions::class, 'order_id', 'id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'order_id', 'id');
    }

    public static function getAllOrder($id)
    {
        return self::with(['carts', 'user', 'address', 'shipping', 'transactions'])->findOrFail($id);
    }

    public static function countActiveOrder()
    {
        return self::count();
    }

    public static function countNewReceivedOrder()
    {
        return self::where('status', 'new')->count();
    }

    public static function countProcessingOrder()
    {
        return self::where('status', 'process')->count();
    }

    public static function countDeliveredOrder()
    {
        return self::where('status', 'delivered')->count();
    }

    public static function countCancelledOrder()
    {
        return self::where('status', 'cancel')->count();
    }
}