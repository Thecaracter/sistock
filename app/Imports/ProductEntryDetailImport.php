<?php

namespace App\Imports;

use App\Models\ProductEntryDetail;
use App\Models\ProductEntry;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class ProductEntryDetailImport implements ToModel, WithHeadingRow
{
    protected $productEntryId;

    public function __construct($productEntryId)
    {
        $this->productEntryId = $productEntryId;
    }

    public function model(array $row)
    {
        // Hitung total untuk detail entry ini
        $total = $row['quantity'] * $row['price'];

        // Mulai transaksi
        DB::beginTransaction();

        try {
            // Buat detail entry
            $detail = new ProductEntryDetail([
                'product_entry_id' => $this->productEntryId,
                'product_id' => $row['product_id'],
                'quantity' => $row['quantity'],
                'price' => $row['price'],
                'total' => $total,
            ]);
            $detail->save();

            // Update total di product entry
            $productEntry = ProductEntry::find($this->productEntryId);
            $productEntry->total += $total;
            $productEntry->save();

            // Commit transaksi
            DB::commit();

            return $detail;

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            throw $e;
        }
    }
}