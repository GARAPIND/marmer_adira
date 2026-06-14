<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->string('kode_resi_internal')->nullable()->after('biaya_pengiriman');
            $table->string('nomor_resi_pengiriman')->nullable()->after('kode_resi_internal');
            $table->timestamp('tanggal_siap_dikirim')->nullable()->after('nomor_resi_pengiriman');
            $table->timestamp('tanggal_dikirim')->nullable()->after('tanggal_siap_dikirim');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn([
                'kode_resi_internal',
                'nomor_resi_pengiriman',
                'tanggal_siap_dikirim',
                'tanggal_dikirim',
            ]);
        });
    }
};
