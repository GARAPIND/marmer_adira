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
        'is_custom',
        'nama_produk',
        'ukuran',
        'jenis_marmer',
        'catatan_khusus',
        'gambar_referensi',
        'jumlah',
        'berat_satuan',
        'total_berat',
        'metode_pengambilan',
        'jenis_pengiriman',
        'alamat_pembeli_id',
        'alamat_pengiriman',
        'biaya_pengiriman',
        'alasan_penolakan',
        'status',
        'total_harga',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'midtrans_payment_type',
        'midtrans_bank',
        'status_pembayaran',
        'jenis_pembayaran',
        'jumlah_dibayar',
        'tanggal_bayar',
        'tanggal_lunas',
        'midtrans_status',
        'midtrans_gross_amount',
        'midtrans_currency',
        'midtrans_fraud_status',
    ];

    protected $casts = [
        'is_custom' => 'boolean',
        'berat_satuan' => 'float',
        'total_berat' => 'float',
        'tanggal_bayar' => 'datetime',
        'tanggal_lunas' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    public function terminal(): BelongsTo
    {
        return $this->belongsTo(Terminal::class);
    }

    public function alamatPembeli(): BelongsTo
    {
        return $this->belongsTo(AlamatPembeli::class);
    }
}
