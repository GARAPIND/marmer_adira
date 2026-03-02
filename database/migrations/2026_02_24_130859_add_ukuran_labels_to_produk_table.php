<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk menambah kolom ukuran.
     */
    public function up(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            // Kita letakkan kolom label ukuran tepat sebelum kolom harganya agar rapi
            $table->string('ukuran_kecil')->nullable()->after('nama_produk');
            $table->string('ukuran_sedang')->nullable()->after('harga_kecil');
            $table->string('ukuran_besar')->nullable()->after('harga_sedang');
        });
    }

    /**
     * Batalkan migrasi (Rollback).
     */
    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropColumn(['ukuran_kecil', 'ukuran_sedang', 'ukuran_besar']);
        });
    }
};