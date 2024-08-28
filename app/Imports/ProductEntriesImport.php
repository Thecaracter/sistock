<?php

namespace App\Imports;

use App\Models\ProductEntry;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductEntriesImport implements ToModel, WithHeadingRow
{
    /**
     * Map the row to the ProductEntry model.
     *
     * @param array $row The row data from the Excel file.
     * @return ProductEntry
     */
    public function model(array $row)
    {
        return new ProductEntry([
            'nama_kapal' => $row['nama_kapal'] ?? null,
            'no_permintaan' => $row['no_permintaan'] ?? null,
            'tgl_permintaan' => Carbon::parse($row['tgl_permintaan'] ?? null), // Ensure date is parsed
            'jenis_barang' => $row['jenis_barang'] ?? null,
            'total' => $row['total'] ?? 0, // Default to 0 if not set
        ]);
    }
}