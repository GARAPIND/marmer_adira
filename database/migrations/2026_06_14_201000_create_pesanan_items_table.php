<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->foreignId('produk_id')->nullable()->constrained('produk')->nullOnDelete();
            $table->boolean('is_custom')->default(false);
            $table->string('nama_produk');
            $table->string('ukuran');
            $table->string('jenis_marmer');
            $table->text('catatan_khusus')->nullable();
            $table->json('gambar_referensi')->nullable();
            $table->unsignedInteger('jumlah')->default(1);
            $table->decimal('berat_satuan', 10, 2)->default(0);
            $table->decimal('total_berat', 10, 2)->default(0);
            $table->bigInteger('harga_satuan')->default(0);
            $table->bigInteger('subtotal')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan_items');
    }
};
