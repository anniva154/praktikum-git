<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laboratorium extends Model
{
    protected $table = 'laboratorium';
    protected $primaryKey = 'id_lab'; // ⬅ PENTING
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_lab',
        'status'
    ];

    public function barang()
    {
        return $this->hasMany(Barang::class, 'id_lab', 'id_lab');
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'id_lab', 'id_lab');
    }
    public function jurusan()
    {
        // Parameter: (NamaModelTarget, foreign_key_di_tabel_lab, owner_key_di_tabel_jurusan)
        return $this->belongsTo(Jurusan::class, 'id_jurusan', 'id_jurusan');
    }
}

