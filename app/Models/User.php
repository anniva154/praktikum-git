<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Jurusan;
  use App\Models\Peminjaman;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * =========================
     * MASS ASSIGNABLE
     * =========================
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'tipe_pengguna',
        'id_jurusan',
        'status',
    ];

    /**
     * =========================
     * HIDDEN
     * =========================
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * =========================
     * CASTING
     * =========================
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * =========================
     * RELATIONSHIP
     * =========================
     * User → Jurusan
     * admin / pimpinan → jurusan_id = null
     */

public function peminjaman()
{
    return $this->hasMany(Peminjaman::class, 'id_user', 'id');
}

   public function jurusan()
{
    return $this->belongsTo(Jurusan::class, 'id_jurusan', 'id_jurusan');
}


    /**
     * =========================
     * ROLE HELPERS
     * =========================
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isKaproli(): bool
    {
        return $this->role === 'kaproli';
    }

    public function isPimpinan(): bool
    {
        return $this->role === 'pimpinan';
    }

   public function isGuru(): bool
{
    return $this->role === 'pengguna' && $this->tipe_pengguna === 'guru';
}

public function isSiswa(): bool
{
    return $this->role === 'pengguna' && $this->tipe_pengguna === 'siswa';
}


    /**
     * =========================
     * STATUS HELPERS
     * =========================
     */
    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }
}
