<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\ProductExit;
use App\Models\ProductExitDetail;
use App\Models\ProductEntryDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductExitDetailsImport implements ToModel, WithHeadingRow
{
    protected $productExitId;

    public function __construct($productExitId)
    {
        $this->productExitId = $productExitId;
    }

    public function model(array $row)
    {
        Log::info('Processing row: ' . json_encode($row));

        DB::beginTransaction();

        try {
            $productEntryDetail = ProductEntryDetail::findOrFail($row['product_entry_detail_id']);

            Log::info('Found ProductEntryDetail: ' . $productEntryDetail->id);

            if ($productEntryDetail->stock < $row['quantity']) {
                Log::warning("Insufficient stock for product entry detail ID {$row['product_entry_detail_id']}. Required: {$row['quantity']}, Available: {$productEntryDetail->stock}");
                throw new \Exception("Stok tidak cukup untuk produk entry detail dengan ID {$row['product_entry_detail_id']}.");
            }

            $detail = new ProductExitDetail([
                'product_exit_id' => $this->productExitId,
                'product_entry_detail_id' => $row['product_entry_detail_id'],
                'quantity' => $row['quantity'],
                'price' => $row['price'],
                'total' => $row['total'],
                'exit_date' => $this->parseDate($row['exit_date']),
            ]);

            $productEntryDetail->stock -= $row['quantity'];
            $productEntryDetail->save();

            Log::info("Updated stock for ProductEntryDetail {$productEntryDetail->id}. New stock: {$productEntryDetail->stock}");

            $productExit = ProductExit::findOrFail($this->productExitId);
            $productExit->total += $row['total'];
            $productExit->save();

            Log::info("Updated total for ProductExit {$this->productExitId}. New total: {$productExit->total}");

            DB::commit();

            Log::info('Successfully processed row');

            return $detail;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing row: ' . $e->getMessage());
            throw $e;
        }
    }

    private function parseDate($date)
    {
        return Carbon::parse($date);
    }
}