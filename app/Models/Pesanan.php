<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pesanan extends Model
{
    use HasFactory, SoftDeletes;

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
        'kode_resi_internal',
        'nomor_resi_pengiriman',
        'tanggal_siap_dikirim',
        'tanggal_dikirim',
        'alasan_penolakan',
        'status',
        'tgl_update_proses',
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
        'midtrans_payload',
    ];

    protected $casts = [
        'is_custom' => 'boolean',
        'berat_satuan' => 'float',
        'total_berat' => 'float',
        'tanggal_bayar' => 'datetime',
        'tanggal_lunas' => 'datetime',
        'tanggal_siap_dikirim' => 'datetime',
        'tanggal_dikirim' => 'datetime',
        'midtrans_payload' => 'array',
        'deleted_at' => 'datetime',
        'gambar_referensi' => 'array',
    ];

    protected $appends = [
        'foto_dikerjakan',
        'foto_selesai',
        'status_label_pembeli',
        'is_menunggu_pelunasan',
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

    public function paymentHistories(): HasMany
    {
        return $this->hasMany(PesananPaymentHistory::class)->orderBy('event_time');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PesananItem::class)->orderBy('id');
    }

    public function progressPhotos(): HasMany
    {
        return $this->hasMany(PhotoProsesPesanan::class)->orderBy('status_target')->orderBy('urutan')->orderBy('id');
    }

    public function progressPhotosDikerjakan(): HasMany
    {
        return $this->hasMany(PhotoProsesPesanan::class)
            ->where('status_target', 'Dikerjakan')
            ->orderBy('urutan')
            ->orderBy('id');
    }

    public function progressPhotosSelesai(): HasMany
    {
        return $this->hasMany(PhotoProsesPesanan::class)
            ->where('status_target', 'Selesai')
            ->orderBy('urutan')
            ->orderBy('id');
    }

    protected function fotoDikerjakan(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->resolveProgressPhotoPaths('Dikerjakan')
        );
    }

    protected function fotoSelesai(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->resolveProgressPhotoPaths('Selesai')
        );
    }

    protected function isMenungguPelunasan(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status === 'Selesai' && $this->status_pembayaran !== 'paid'
        );
    }

    protected function statusLabelPembeli(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->status === 'diekspedisi') {
                    return 'Dikirim';
                }

                if ($this->status === 'Siap Dikirim') {
                    return 'Siap Dikirim';
                }

                if ($this->status === 'Selesai' && $this->status_pembayaran !== 'paid') {
                    return 'Menunggu Pelunasan';
                }

                return $this->status;
            }
        );
    }

    private function resolveProgressPhotoPaths(string $statusTarget): array
    {
        if ($this->relationLoaded('progressPhotos')) {
            return $this->progressPhotos
                ->where('status_target', $statusTarget)
                ->pluck('photo_path')
                ->values()
                ->all();
        }

        return $this->progressPhotos()
            ->where('status_target', $statusTarget)
            ->pluck('photo_path')
            ->values()
            ->all();
    }
}
