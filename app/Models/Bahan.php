<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bahan extends Model
{
    use HasFactory;

    // WAJIB: Tambahkan baris ini untuk memberitahu nama tabel yang benar
    protected $table = 'bahan'; 

    protected $fillable = ['nama_bahan'];
}