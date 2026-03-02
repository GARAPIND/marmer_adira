<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SinkronisasiHargaSeeder extends Seeder
{
    public function run()
    {
        // Data Matrix Anda
        $data = [
            'ASBAK' => [
                'Teraso' => ['kecil' => 20000, 'sedang' => 35000, 'besar' => 55000],
                'Andesit' => ['kecil' => 28000, 'sedang' => 48000, 'besar' => 75000],
                'Marmer' => ['kecil' => 35000, 'sedang' => 60000, 'besar' => 95000],
                'Granit' => ['kecil' => 42000, 'sedang' => 72000, 'besar' => 115000],
                'Onix'   => ['kecil' => 85000, 'sedang' => 150000, 'besar' => 240000],
            ],
            'TEMPAT SABUN' => [
                'Teraso' => ['kecil' => 65000, 'sedang' => 100000, 'besar' => 120000],
                'Marmer' => ['kecil' => 105000, 'sedang' => 170000, 'besar' => 200000],
                'Onyx'   => ['kecil' => 260000, 'sedang' => 425000, 'besar' => 500000], // Onyx di DB Anda pakai 'y'
            ],
            'VAS BUNGA' => [
                'Teraso' => ['kecil' => 55000, 'sedang' => 115000, 'besar' => 255000],
                'Marmer' => ['kecil' => 95000, 'sedang' => 195000, 'besar' => 425000],
                'Onyx'   => ['kecil' => 240000, 'sedang' => 485000, 'besar' => 1060000],
            ],
            'WASTAFEL' => [
                'Teraso' => ['kecil' => 330000, 'sedang' => 510000, 'besar' => 660000],
                'Marmer' => ['kecil' => 550000, 'sedang' => 850000, 'besar' => 1100000],
            ]
        ];

        foreach ($data as $namaProduk => $bahans) {
            foreach ($bahans as $bahan => $harga) {
                DB::table('produk')
                    ->where('nama_produk', $namaProduk)
                    ->where('bahan', $bahan)
                    ->update([
                        'harga_kecil'  => $harga['kecil'],
                        'harga_sedang' => $harga['sedang'],
                        'harga_besar'  => $harga['besar'],
                        'updated_at'   => now(),
                    ]);
            }
        }

        $this->command->info('Database berhasil disinkronkan dengan Matrix Harga!');
    }
}