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
            $table->renameColumn('tarif_per_km', 'tarif_per_kg');
            $table->dropColumn('jarak_km');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('weight_based', function (Blueprint $table) {
            //
        });
    }
};
