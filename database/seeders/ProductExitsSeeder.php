<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\ProductExit;
use App\Models\ProductExitDetail;

class ProductExitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        // Membuat 2 entri untuk ProductExit
        for ($i = 0; $i < 2; $i++) {
            $productExit = ProductExit::create([
                'nama_kapal' => $faker->word,
                'no_exit' => $faker->unique()->numberBetween(1000, 9999),
                'tgl_exit' => $faker->date(),
                'jenis_barang' => $faker->word,
                'total' => 0, // Akan diupdate setelah menambahkan detail
            ]);

            // Simpan ID untuk keperluan seeder detail
            $productExitIds[] = $productExit->id;

            // Membuat 3 entri untuk ProductExitDetail
            for ($j = 0; $j < 3; $j++) {
                $quantity = $faker->numberBetween(1, 10);
                $price = $faker->numberBetween(10000, 50000);
                $total = $quantity * $price;

                // Membuat detail keluar produk
                ProductExitDetail::create([
                    'product_exit_id' => $productExit->id,
                    'product_entrie_detail_id' => rand(1, 10), // Ganti dengan ID yang valid sesuai data di tabel product_entries_detail
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $total,
                    'exit_date' => $productExit->tgl_exit,
                ]);

                // Mengupdate total untuk ProductExit
                $productExit->total += $total;
            }

            // Simpan total setelah semua detail ditambahkan
            $productExit->save();
        }
    }
}
