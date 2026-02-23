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
    'status',      // ⬅️ TAMBAHKAN INI
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
  

public function dashboardKaproli()
{
    $idJurusan = auth()->user()->id_jurusan;

    // Total stok barang semua lab dalam 1 jurusan
    $barang = Barang::where('id_jurusan', $idJurusan)
                    ->sum('jumlah');

    // Kalau mau jumlah jenis barang:
    // $barang = Barang::where('id_jurusan', $idJurusan)->count();

    return view('backend.kaproli.dashboard', compact('barang'));
}



}
