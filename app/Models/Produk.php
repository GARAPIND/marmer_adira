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
        'berat_kecil',
        'bahan_kecil_id',
        'ukuran_sedang',
        'harga_sedang',
        'berat_sedang',
        'bahan_sedang_id',
        'ukuran_besar',
        'harga_besar',
        'berat_besar',
        'bahan_besar_id',
        'stok',
        'gambar',
        'pengrajin_id',
    ];

    public function pengrajin()
    {
        return $this->belongsTo(User::class, 'pengrajin_id');
    }
    public function bahan_kecil()
    {
        return $this->belongsTo(Bahan::class, 'bahan_kecil_id');
    }
    public function bahan_sedang()
    {
        return $this->belongsTo(Bahan::class, 'bahan_sedang_id');
    }
    public function bahan_besar()
    {
        return $this->belongsTo(Bahan::class, 'bahan_besar_id');
    }
}
