<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Perintah untuk mengubah nama tabel dari bahans ke bahan
        Schema::rename('bahans', 'bahan');
    }

    public function down(): void
    {
        // Perintah untuk mengembalikan nama jika migrasi di-rollback
        Schema::rename('bahan', 'bahans');
    }
};