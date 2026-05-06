<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            if (!Schema::hasColumn('pesanan', 'midtrans_payload')) {
                $table->longText('midtrans_payload')->nullable()->after('midtrans_fraud_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            if (Schema::hasColumn('pesanan', 'midtrans_payload')) {
                $table->dropColumn('midtrans_payload');
            }
        });
    }
};
