<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Jurusan extends Model
{
    use HasFactory;

    protected $table = 'jurusan';
    protected $primaryKey = 'id_jurusan';

    // PK bukan integer auto default Laravel
    protected $keyType = 'int';
    public $incrementing = true;

    // Karena created_at & updated_at NULL
    public $timestamps = false;

    protected $fillable = [
        'nama_jurusan',
    ];

    /**
     * =========================
     * RELATIONSHIP
     * =========================
     * Jurusan memiliki banyak User
     */
    public function users()
    {
        return $this->hasMany(User::class, 'id_jurusan', 'id_jurusan');
    }
}
