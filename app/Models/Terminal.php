<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terminal extends Model
{
    use HasFactory;

    // Pastikan nama tabel benar
    protected $table = 'terminals';

    // WAJIB tambahkan ini agar seeder bisa memasukkan data
    protected $fillable = [
        'nama_terminal',
        'jarak_km',
        'tarif_per_km',
        'tarif', // Tambahkan ini agar tidak error 'default value' lagi
    ];
}