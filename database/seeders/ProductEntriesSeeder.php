<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductEntry;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductEntriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            ProductEntry::create([
                'nama_kapal' => 'Kapal Laut ' . $i,
                'no_permintaan' => 'REQ000' . $i,
                'tgl_permintaan' => now()->addDays($i),
                'jenis_barang' => 'Jenis ' . chr(64 + $i),
                'total' => intval(rand(10000, 30000) / 100),
            ]);
        }
    }

}
