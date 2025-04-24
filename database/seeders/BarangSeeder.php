<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('barangs')->insert([
            [
                'nama_barang' => 'Laptop ASUS ROG',
                'stok' => 10,
                'harga' => 25000000.00,
                'kategori' => 1, // ID kategori Elektronik
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_barang' => 'Smartphone Samsung Galaxy S23',
                'stok' => 15,
                'harga' => 15000000.00,
                'kategori' => 1, // ID kategori Elektronik
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
