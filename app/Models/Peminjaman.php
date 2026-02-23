<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $primaryKey = 'id_peminjaman';
protected $casts = [
    'waktu_pinjam'  => 'datetime',
    'waktu_kembali' => 'datetime',
];


    protected $fillable = [
        'id_user',
        'id_barang',
        'waktu_pinjam',
        'waktu_kembali',
        'jumlah_pinjam',
        'status',
        'keterangan',
    ];
    public function barang()
{
    return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
}

public function user()
{
    return $this->belongsTo(User::class, 'id_user', 'id');
}

}
