<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKerusakan extends Model
{
    protected $table = 'laporan_kerusakan';
    protected $primaryKey = 'id_laporan';

    protected $fillable = [
        'id_user', 'id_barang', 'id_lab', 'tgl_laporan',
        'foto', 'keterangan', 'status', 'updated_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function laboratorium()
    {
        return $this->belongsTo(Lab::class, 'id_lab');
    }
}
