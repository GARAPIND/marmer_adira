<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Sesuaikan nama tabelnya menjadi 'pesanan'
        Schema::table('pesanan', function (Blueprint $table) {
            $table->string('terminal_tujuan')->nullable();
            $table->decimal('estimasi_ongkir_bus', 10, 2)->default(0);
        });
    }

    public function down()
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn(['terminal_tujuan', 'estimasi_ongkir_bus']);
        });
    }
};
