<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesananItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesanan_id',
        'produk_id',
        'is_custom',
        'nama_produk',
        'ukuran',
        'jenis_marmer',
        'catatan_khusus',
        'gambar_referensi',
        'jumlah',
        'berat_satuan',
        'total_berat',
        'harga_satuan',
        'subtotal',
    ];

    protected $casts = [
        'is_custom' => 'boolean',
        'gambar_referensi' => 'array',
        'berat_satuan' => 'float',
        'total_berat' => 'float',
    ];

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }
}
