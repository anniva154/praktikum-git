<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanBarang extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_barang';
    protected $primaryKey = 'id_pengajuan';

    // Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'id_user',
        'id_jurusan',
        'id_lab',
        'nama_barang',
        'spesifikasi',
        'jumlah',
        'satuan',
        'estimasi_harga',
        'urgensi',
        'alasan_kebutuhan',
        'status_persetujuan',
        'catatan_pimpinan',
    ];
    // app/Models/PengajuanBarang.php

public function user()
{
    // Hubungkan ke model User menggunakan kolom id_user
    return $this->belongsTo(User::class, 'id_user');
}

 public function laboratorium()
    {
        return $this->belongsTo(Laboratorium::class, 'id_lab');
    }

    // Relasi ke Jurusan
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan', 'id_jurusan');
    }

    // Relasi ke Laboratorium
   
}