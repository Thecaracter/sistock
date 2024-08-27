<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductEntryDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            $quantity = $faker->numberBetween(1, 100);
            $price = $faker->numberBetween(10000, 50000);
            $total = $quantity * $price;

            DB::table('product_entries_detail')->insert([
                'product_entry_id' => rand(1, 10), // Pastikan ID ini sesuai dengan data di tabel product_entries
                'product_id' => rand(1, 3), // Pastikan ID ini sesuai dengan data di tabel products
                'quantity' => $quantity,
                'price' => $price,
                'total' => $total,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
