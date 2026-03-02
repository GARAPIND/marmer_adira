<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(ProdukSeeder::class);

        // Akun ADMIN - Tanpa Hash::make
        User::create([
            'name'      => 'Admin Adira Marmer',
            'email'     => 'admin@gmail.com',
            'password'  => 'admin123', 
            'no_telp'   => '081122334455',
            'role'      => 'admin', 
        ]);

        // Akun PENGRAJIN - Tanpa Hash::make
        User::create([
            'name'      => 'Hadi Pengrajin',
            'email'     => 'hadi@gmail.com',
            'password'  => 'hadi123', 
            'no_telp'   => '085566778899',
            'role'      => 'pengrajin', 
        ]);
    }
}