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
        'midtrans_order_id',
        'midtrans_transaction_id',
        'midtrans_payment_type',
        'status_pembayaran',
        'tanggal_bayar',
        'midtrans_status',
        'midtrans_gross_amount',
        'midtrans_currency',
        'midtrans_fraud_status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    public function terminal()
    {
        return $this->belongsTo(Terminal::class);
    }
}
