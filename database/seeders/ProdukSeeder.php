<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        // 1. BERSIHKAN TABEL (Agar ID mulai dari 1 dan tidak ada data sampah)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('produk')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. SINKRONISASI TERMINAL (Berdasarkan tarif per kg)
        $terminals = [
            ['nama' => 'Terminal Tamanan (Kediri)', 'harga' => 1500],
            ['nama' => 'Terminal Patria (Blitar)', 'harga' => 1500],
            ['nama' => 'Terminal Arjosari (Malang)', 'harga' => 1200],
            ['nama' => 'Terminal Bungurasih (Surabaya)', 'harga' => 1000],
        ];

        $kolomHarga = Schema::hasColumn('terminals', 'tarif_per_kg') ? 'tarif_per_kg' : 'tarif_per_km';

        foreach ($terminals as $t) {
            DB::table('terminals')->where('nama_terminal', $t['nama'])->update([
                $kolomHarga => $t['harga'],
                'updated_at' => now(),
            ]);
        }

        // 3. DATA MATRIX PRODUK (Menyinkronkan K, S, B dengan spesifikasi teks kamu)
        $produkMatrix = [
            'ASBAK' => [
                'Teraso' => ['k' => 20000, 's' => 35000, 'b' => 55000],
                'Marmer' => ['k' => 35000, 's' => 60000, 'b' => 95000],
                'Onyx'   => ['k' => 85000, 's' => 150000, 'b' => 240000],
                // Catatan: Jumbo (D 20) bisa dimasukkan ke kolom harga_besar jika hanya ada 3 kolom
            ],
            'TEMPAT SABUN' => [
                'Teraso' => ['k' => 65000, 's' => 100000, 'b' => 120000],
                'Marmer' => ['k' => 105000, 's' => 170000, 'b' => 200000],
                'Onyx'   => ['k' => 260000, 's' => 425000, 'b' => 500000],
            ],
            'VAS BUNGA' => [
                'Teraso' => ['k' => 55000, 's' => 115000, 'b' => 255000],
                'Marmer' => ['k' => 95000, 's' => 195000, 'b' => 425000],
                'Onyx'   => ['k' => 240000, 's' => 485000, 'b' => 1060000],
            ],
            'WASTAFEL' => [
                'Teraso' => ['k' => 330000, 's' => 510000, 'b' => 660000],
                'Marmer' => ['k' => 550000, 's' => 850000, 'b' => 1100000],
            ],
            'UBIN' => [
                'Teraso' => ['k' => 90000, 's' => 210000, 'b' => 0],
                'Marmer' => ['k' => 150000, 's' => 350000, 'b' => 850000],
            ]
        ];

        foreach ($produkMatrix as $nama => $bahans) {
            foreach ($bahans as $bahan => $harga) {
                DB::table('produk')->insert([
                    'nama_produk' => $nama,
                    'bahan'       => $bahan,
                    'harga_kecil'  => $harga['k'],
                    'harga_sedang' => $harga['s'],
                    'harga_besar'  => $harga['b'],
                    'stok'         => 0,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }
        }

        $this->command->info("Database berhasil direset dan disinkronkan sesuai ukuran matrix!");
    }
}