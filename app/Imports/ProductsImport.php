<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    /**
     * Fungsi ini untuk menentukan bagaimana setiap baris data diimpor ke dalam model.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Product([
            'code_barang' => $row['code_barang'],
            'name' => $row['name'],
            'part_code' => $row['part_code'],
            'image' => $row['image'],
            'merk' => $row['merk'],
        ]);
    }
}
