<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan_payment_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->string('event_type');
            $table->string('payment_step')->nullable();
            $table->string('event_reference');
            $table->string('source')->nullable();
            $table->string('status')->nullable();
            $table->string('order_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->bigInteger('nominal')->default(0);
            $table->string('currency', 10)->default('IDR');
            $table->timestamp('event_time')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->unique(['pesanan_id', 'event_type', 'event_reference'], 'pesanan_payment_histories_unique_event');
            $table->index(['pesanan_id', 'event_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan_payment_histories');
    }
};
