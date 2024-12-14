<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions'; // Pastikan nama tabel sesuai jika berbeda dari konvensi

    protected $fillable = [
        'order_id',
        'customer_name',
        'gross_amount',
        'payment_type',
        'payment_method',
        'courier',
        'courier_service',
        'transaction_status',
        'transaction_time',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}

