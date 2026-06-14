<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $pesanans = DB::table('pesanan')->whereNotNull('gambar_referensi')->get();
        foreach ($pesanans as $p) {
            $val = $p->gambar_referensi;
            if (!is_null($val) && !str_starts_with(trim($val), '[')) {
                DB::table('pesanan')->where('id', $p->id)->update([
                    'gambar_referensi' => json_encode([$val]),
                ]);
            }
        }

        Schema::table('pesanan', function (Blueprint $table) {
            $table->json('gambar_referensi')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->string('gambar_referensi')->nullable()->change();
        });
    }
};
