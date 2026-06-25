<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bahan', function (Blueprint $table) {
            $table->json('foto_sampel')->nullable()->after('nama_bahan');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->string('foto_sampel_terpilih')->nullable()->after('catatan_khusus');
        });

        Schema::table('pesanan', function (Blueprint $table) {
            $table->string('foto_sampel_terpilih')->nullable()->after('catatan_khusus');
        });

        Schema::table('pesanan_items', function (Blueprint $table) {
            $table->string('foto_sampel_terpilih')->nullable()->after('catatan_khusus');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan_items', function (Blueprint $table) {
            $table->dropColumn('foto_sampel_terpilih');
        });

        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn('foto_sampel_terpilih');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropColumn('foto_sampel_terpilih');
        });

        Schema::table('bahan', function (Blueprint $table) {
            $table->dropColumn('foto_sampel');
        });
    }
};
