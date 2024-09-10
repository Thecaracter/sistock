<?php

namespace App\Imports;

use App\Models\ProductExit;
use App\Models\ProductExitDetail;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class ProductExitImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            0 => new ProductExitSheetImport(),
            1 => new ProductExitDetailSheetImport(),
        ];
    }
}

class ProductExitSheetImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Check if 'tanggal_exit' exists in the row, if not, use current date
        $tgl_exit = isset($row['tanggal_exit']) && !empty($row['tanggal_exit'])
            ? Carbon::parse($row['tanggal_exit'])
            : Carbon::now();

        return new ProductExit([
            'nama_kapal' => $row['nama_kapal'],
            'no_exit' => $row['no_exit'],
            'tgl_exit' => $tgl_exit,
            'jenis_barang' => $row['jenis_barang'],
            'total' => is_numeric($row['total']) ? (int) round((float) $row['total']) : 0,
        ]);
    }
}

class ProductExitDetailSheetImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $exit_date = isset($row['exit_date']) && !empty($row['exit_date'])
            ? Carbon::parse($row['exit_date'])
            : Carbon::now();

        return new ProductExitDetail([
            'product_exit_id' => $row['product_exit_id'],
            'product_entry_detail_id' => $row['product_entry_detail_id'],
            'quantity' => is_numeric($row['quantity']) ? (int) $row['quantity'] : 0,
            'price' => is_numeric($row['price']) ? (float) $row['price'] : 0,
            'total' => is_numeric($row['total']) ? (float) $row['total'] : 0,
            'exit_date' => $exit_date,
        ]);
    }
}