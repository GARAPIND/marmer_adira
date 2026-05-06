<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->boolean('is_custom')->default(true)->after('user_id');
            $table->decimal('berat_satuan', 10, 2)->default(0)->after('jumlah');
            $table->decimal('total_berat', 10, 2)->default(0)->after('berat_satuan');

            $table->string('jenis_pembayaran')->nullable()->after('status_pembayaran');
            $table->bigInteger('jumlah_dibayar')->default(0)->after('jenis_pembayaran');
            $table->timestamp('tanggal_lunas')->nullable()->after('tanggal_bayar');
            $table->string('midtrans_bank')->nullable()->after('midtrans_payment_type');
        });

        DB::statement("ALTER TABLE pesanan MODIFY status_pembayaran ENUM('no_paid', 'dp', 'paid') DEFAULT 'no_paid'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pesanan MODIFY status_pembayaran ENUM('no_paid', 'paid') DEFAULT 'no_paid'");

        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn([
                'is_custom',
                'berat_satuan',
                'total_berat',
                'jenis_pembayaran',
                'jumlah_dibayar',
                'tanggal_lunas',
                'midtrans_bank',
            ]);
        });
    }
};
