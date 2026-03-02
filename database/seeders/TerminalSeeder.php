<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Terminal;

class TerminalSeeder extends Seeder
{
    public function run(): void
{
    $data = [
        ['nama_terminal' => 'Terminal Tamanan (Kediri)', 'jarak_km' => 30, 'tarif_per_km' => 1500, 'tarif' => 0],
        ['nama_terminal' => 'Terminal Patria (Blitar)', 'jarak_km' => 40, 'tarif_per_km' => 1500, 'tarif' => 0],
        ['nama_terminal' => 'Terminal Arjosari (Malang)', 'jarak_km' => 100, 'tarif_per_km' => 1200, 'tarif' => 0],
        ['nama_terminal' => 'Terminal Bungurasih (Surabaya)', 'jarak_km' => 160, 'tarif_per_km' => 1000, 'tarif' => 0],
    ];

    foreach ($data as $item) {
        \App\Models\Terminal::updateOrCreate(
            ['nama_terminal' => $item['nama_terminal']], 
            $item
        );
    }
}
}