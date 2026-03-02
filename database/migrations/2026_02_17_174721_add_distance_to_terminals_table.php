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
        Schema::table('terminals', function (Blueprint $table) {
            // Menambahkan kolom baru tanpa menghapus data lama
            if (!Schema::hasColumn('terminals', 'jarak_km')) {
                $table->integer('jarak_km')->default(0)->after('nama_terminal');
                $table->integer('tarif_per_km')->default(1000)->after('jarak_km');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('terminals', function (Blueprint $table) {
            //
        });
    }
};
