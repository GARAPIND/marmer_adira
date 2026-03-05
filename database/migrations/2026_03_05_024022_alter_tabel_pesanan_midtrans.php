<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->string('midtrans_order_id')->nullable()->after('total_harga');
            $table->string('midtrans_transaction_id')->nullable()->after('midtrans_order_id');
            $table->string('midtrans_payment_type')->nullable()->after('midtrans_transaction_id');
            $table->enum('status_pembayaran', ['no_paid', 'paid'])->default('no_paid')->after('midtrans_payment_type');
            $table->timestamp('tanggal_bayar')->nullable()->after('status_pembayaran');
            $table->string('midtrans_status')->nullable()->after('tanggal_bayar');
            $table->decimal('midtrans_gross_amount', 15, 2)->nullable()->after('midtrans_status');
            $table->string('midtrans_currency')->nullable()->after('midtrans_gross_amount');
            $table->string('midtrans_fraud_status')->nullable()->after('midtrans_currency');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn([
                'midtrans_order_id',
                'midtrans_transaction_id',
                'midtrans_payment_type',
                'status_pembayaran',
                'tanggal_bayar',
                'midtrans_status',
                'midtrans_gross_amount',
                'midtrans_currency',
                'midtrans_fraud_status',
            ]);
        });
    }
};
