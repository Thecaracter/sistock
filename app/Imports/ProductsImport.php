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
            'name' => $row['name'],         // Nama kolom dalam Excel
            'description' => $row['description'],  // Nama kolom dalam Excel
            'image' => $row['image'],        // Nama kolom dalam Excel (path gambar)
        ]);
    }
}
