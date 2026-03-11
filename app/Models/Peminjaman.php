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
    'waktu_pinjam',
    'waktu_kembali',
    'tanggal_dikembalikan', // Tambahkan ini
    'jumlah_pinjam',
    'status',
    'keterangan',
];

protected $casts = [
    'waktu_pinjam'         => 'datetime',
    'waktu_kembali'        => 'datetime',
    'tanggal_dikembalikan' => 'datetime', // Tambahkan ini
];
    public function barang()
{
    return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
}


public function user()
{
    return $this->belongsTo(User::class, 'id_user', 'id');
}
// --- TAMBAHKAN RELASI INI ---
    public function laboratorium()
    {
        return $this->belongsTo(Lab::class, 'id_lab');
    }
}
