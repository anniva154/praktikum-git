<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shippings extends Model
{
    use HasFactory;

    protected $table = 'shippings';

    protected $fillable = [
        'order_id', 
        'tracking_number', 
        'status', 
        'shipping_method'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
