<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bahan extends Model
{
    use HasFactory;

    protected $table = 'bahan';

    protected $fillable = [
        'nama_bahan',
        'foto_sampel',
    ];

    protected $casts = [
        'foto_sampel' => 'array',
    ];
}
