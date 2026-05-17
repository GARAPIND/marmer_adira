<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->json('foto_dikerjakan')->nullable()->after('gambar_referensi');
            $table->json('foto_selesai')->nullable()->after('foto_dikerjakan');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn(['foto_dikerjakan', 'foto_selesai']);
        });
    }
};
