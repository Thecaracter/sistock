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
        for ($i = 0; $i < 10; $i++) {
            ProductEntry::create([
                'product_id' => rand(1, Product::count()),
                'quantity' => rand(10, 100),
                'price' => rand(10000, 50000),
                'total' => rand(10, 100) * rand(10000, 50000),
                'entry_date' => Carbon::now()->subDays(rand(0, 30))->format('Y-m-d'),
            ]);
        }
    }

}
