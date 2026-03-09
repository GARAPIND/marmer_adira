<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alamat_pembeli', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('label');
            $table->string('nama_penerima');
            $table->string('no_telepon', 20);
            $table->text('alamat_lengkap');
            $table->string('provinsi_id');
            $table->string('provinsi_nama');
            $table->string('kota_id');
            $table->string('kota_nama');
            $table->string('kecamatan_id');
            $table->string('kecamatan_nama');
            $table->string('kode_pos', 10)->nullable();
            $table->boolean('is_utama')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alamat_pembeli');
    }
};
