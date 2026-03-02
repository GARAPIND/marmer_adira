<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel users (Pembeli)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Kolom sesuai Form Pemesanan
            $table->string('nama_produk'); 
            $table->string('ukuran');
            $table->string('jenis_marmer');
            $table->text('catatan_khusus')->nullable();
            $table->string('gambar_referensi')->nullable(); 
            $table->integer('jumlah');
            $table->string('metode_pengambilan')->nullable(); // "dirumah" atau "dikirim"

            // TAMBAHAN BARU: Alamat & Biaya Bus
            $table->string('alamat_pengiriman')->nullable(); 
            $table->bigInteger('biaya_pengiriman')->default(0); 
            
            // Kolom Status & Harga
            $table->string('status')->default('Menunggu Verifikasi Admin');
            $table->bigInteger('total_harga')->default(0); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};