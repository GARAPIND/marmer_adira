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
            $table->string('bahan')->nullable()->change();
            $table->bigInteger('harga_kecil')->nullable()->change();
            $table->bigInteger('harga_sedang')->nullable()->change();
            $table->bigInteger('harga_besar')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->string('bahan')->nullable(false)->change();
            $table->bigInteger('harga_kecil')->nullable(false)->change();
            $table->bigInteger('harga_sedang')->nullable(false)->change();
            $table->bigInteger('harga_besar')->nullable(false)->change();
        });
    }
};
