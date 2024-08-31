<?php

namespace App\Exports;

use App\Models\ProductExit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductExitExport implements FromCollection, WithHeadings
{
    /**
     * Data yang akan diexport.
     */
    public function collection()
    {
        return ProductExit::select('nama_kapal', 'no_exit', 'tgl_exit', 'jenis_barang', 'total')->get();
    }

    /**
     * Header kolom untuk file Excel.
     */
    public function headings(): array
    {
        return [
            'Nama Kapal',
            'No Exit',
            'Tanggal Exit',
            'Jenis Barang',
            'Total'
        ];
    }
}

