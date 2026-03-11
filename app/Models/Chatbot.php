<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Tambahkan/cek baris ini

class Chatbot extends Model
{
    use HasFactory;

    protected $fillable = ['keyword', 'jawaban'];
}