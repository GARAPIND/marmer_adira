<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlamatPembeli extends Model
{
    use HasFactory;

    protected $table = 'alamat_pembeli';

    protected $fillable = [
        'user_id',
        'label',
        'nama_penerima',
        'no_telepon',
        'alamat_lengkap',
        'provinsi_id',
        'provinsi_nama',
        'kota_id',
        'kota_nama',
        'kecamatan_id',
        'kecamatan_nama',
        'kode_pos',
        'is_utama',
    ];

    protected $casts = [
        'is_utama' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
