<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductExitDetail;
use App\Models\ProductEntryDetail;
use Faker\Factory as Faker;

class ProductExitDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 2; $i++) {
            // Ambil produk entry detail yang ada untuk digunakan
            $productEntryDetail = ProductEntryDetail::inRandomOrder()->first();

            if ($productEntryDetail) {
                $currentStock = $productEntryDetail->stock;

                // Cek apakah cukup stok untuk keluar
                $quantity = $faker->numberBetween(1, min(50, $currentStock));
                $price = $productEntryDetail->price;
                $total = $quantity * $price;

                // Membuat entri baru ke product_exits_detail
                $exitDetail = ProductExitDetail::create([
                    'product_exit_id' => rand(1, 2), // Pastikan ID ini sesuai dengan data di tabel product_exits
                    'product_entrie_detail_id' => $productEntryDetail->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $total,
                    'exit_date' => now(),
                ]);

                // Mengurangi stock di product_entries_detail
                $exitDetail->decrementStock();

                // Debugging
                \Log::info("Exit Detail Created: ", $exitDetail->toArray());
            }
        }
    }
}
