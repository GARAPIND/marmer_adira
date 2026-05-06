<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesananPaymentHistory extends Model
{
    use HasFactory;

    protected $table = 'pesanan_payment_histories';

    protected $fillable = [
        'pesanan_id',
        'event_type',
        'payment_step',
        'event_reference',
        'source',
        'status',
        'order_id',
        'transaction_id',
        'payment_method',
        'nominal',
        'currency',
        'event_time',
        'payload',
    ];

    protected $casts = [
        'event_time' => 'datetime',
        'payload' => 'array',
    ];

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class);
    }
}
