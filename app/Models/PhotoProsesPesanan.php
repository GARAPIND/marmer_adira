<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhotoProsesPesanan extends Model
{
    use HasFactory;

    protected $table = 'photo_proses_pesanan';

    protected $fillable = [
        'pesanan_id',
        'status_target',
        'photo_path',
        'urutan',
    ];

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class);
    }
}
