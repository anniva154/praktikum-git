<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $primaryKey = 'id_peminjaman';

    protected $fillable = [
        'id_user',
        'id_barang',
        'tgl_pinjam',
        'tgl_kembali',
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
