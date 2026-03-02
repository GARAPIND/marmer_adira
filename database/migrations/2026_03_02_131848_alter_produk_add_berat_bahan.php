<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->unsignedBigInteger('bahan_kecil_id')->nullable()->after('harga_kecil');
            $table->bigInteger('berat_kecil')->nullable()->after('bahan_kecil_id');
            $table->unsignedBigInteger('bahan_sedang_id')->nullable()->after('harga_sedang');
            $table->bigInteger('berat_sedang')->nullable()->after('bahan_sedang_id');
            $table->unsignedBigInteger('bahan_besar_id')->nullable()->after('harga_besar');
            $table->bigInteger('berat_besar')->nullable()->after('bahan_besar_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropColumn(['bahan_kecil_id', 'berat_kecil', 'bahan_sedang_id', 'berat_sedang', 'bahan_besar_id', 'berat_besar']);
        });
    }
};
