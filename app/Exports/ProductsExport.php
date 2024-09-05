<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{
    /**
     * Method untuk mengambil data yang akan diekspor.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Product::all(['code_barang', 'name', 'part_code', 'image', 'merk']); // Tentukan kolom yang akan diekspor
    }

    /**
     * Method untuk menentukan heading.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Code Barang',
            'Name',
            'Part Code',
            'Image',
            'Merk'
        ];
    }
}
