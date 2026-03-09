<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->string('jenis_pengiriman')->nullable()->after('metode_pengambilan');
            $table->unsignedBigInteger('alamat_pembeli_id')->nullable()->after('jenis_pengiriman');
            $table->foreign('alamat_pembeli_id')->references('id')->on('alamat_pembeli')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropForeign(['alamat_pembeli_id']);
            $table->dropColumn(['jenis_pengiriman', 'alamat_pembeli_id']);
        });
    }
};
