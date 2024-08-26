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
                'name' => 'Produk A',
                'description' => 'Deskripsi Produk A',
                'image' => 'produk_a.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Produk B',
                'description' => 'Deskripsi Produk B',
                'image' => 'produk_b.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Produk C',
                'description' => 'Deskripsi Produk C',
                'image' => 'produk_c.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
