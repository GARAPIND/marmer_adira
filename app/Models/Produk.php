<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    // Sesuai migrasi Sugeng: Schema::table('produk', ...)
    protected $table = 'produk';

    protected $fillable = [
        'nama_produk',
        'bahan',
        'deskripsi',
        'ukuran_kecil', 
        'harga_kecil',
        'ukuran_sedang', 
        'harga_sedang',
        'ukuran_besar', 
        'harga_besar',
        'stok',
        'gambar',
        'pengrajin_id', 
    ];

    public function pengrajin() {
        return $this->belongsTo(User::class, 'pengrajin_id');
    }
}