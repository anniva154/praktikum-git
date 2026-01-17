<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    protected $table = 'laboratorium';
    protected $primaryKey = 'id_lab';

    protected $fillable = [
        'nama_lab',
        'id_jurusan',
        'status',
        'keterangan', // ← INI WAJIB
    ];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan', 'id_jurusan');
    }
}
