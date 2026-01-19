<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';

    protected $fillable = [
        'nama_barang',
        'kode_barang',
        'jumlah',
        'kondisi',
        'id_jurusan',
        'id_lab'
    ];
     // BARANG -> LAB
    public function laboratorium()
    {
        return $this->belongsTo(Laboratorium::class, 'id_lab', 'id_lab');
    }

    // BARANG -> JURUSAN
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan', 'id_jurusan');
    }
    public function getRouteKeyName()
{
    return 'id_barang';
}

}
