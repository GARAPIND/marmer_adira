<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk'); 
            $table->string('bahan');        
            $table->text('deskripsi')->nullable();
            $table->bigInteger('harga_kecil')->default(0);  
            $table->bigInteger('harga_sedang')->default(0); 
            $table->bigInteger('harga_besar')->default(0);  
            $table->integer('stok')->default(0);
            $table->string('gambar')->nullable();
            
            // TAMBAHKAN INI: Menghubungkan produk ke pengrajin (User)
            $table->foreignId('pengrajin_id')->nullable()->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};