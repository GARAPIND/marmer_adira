<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';

    protected $fillable = [
        'user_id',
        'nama_produk',
        'ukuran',
        'jenis_marmer',
        'catatan_khusus',
        'gambar_referensi',
        'jumlah',
        'metode_pengambilan',
    
        'alamat_pengiriman', 
        'biaya_pengiriman',
        'status',
        'total_harga',
    ];

    /**
     * Relasi ke User (Pembeli)
     * Satu pesanan dimiliki oleh satu user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Produk
     * Satu pesanan berisi satu jenis produk marmer.
     */
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }
    public function terminal()
    {
        return $this->belongsTo(Terminal::class);
    }
}