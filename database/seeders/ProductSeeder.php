<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('product')->insert([
            [
                'code_barang' => 'AAA123',
                'name' => 'Produk A',
                'part_code' => 'abc123',
                'image' => 'produk_a.jpg',
                'merk' => 'Merk A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code_barang' => 'BBB456',
                'name' => 'Produk B',
                'part_code' => 'abc456',
                'image' => 'produk_b.jpg',
                'merk' => 'Merk B',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code_barang' => 'CCC789',
                'name' => 'Produk C',
                'part_code' => 'abc789',
                'image' => 'produk_c.jpg',
                'merk' => 'Merk C',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
