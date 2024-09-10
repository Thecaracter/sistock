<?php

namespace App\Exports;

use App\Models\ProductEntry;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductEntriesExport implements FromQuery, WithHeadings, WithTitle, WithMapping
{
    public function query()
    {
        return ProductEntry::query();
    }

    public function map($productEntry): array
    {
        return [
            $productEntry->id,
            $productEntry->nama_kapal,
            $productEntry->no_permintaan,
            $productEntry->tgl_permintaan,
            $productEntry->jenis_barang,
            $productEntry->total,
            $productEntry->created_at,
            $productEntry->updated_at,
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Kapal',
            'No Permintaan',
            'Tanggal Permintaan',
            'Jenis Barang',
            'Total',
            'Created At',
            'Updated At',
        ];
    }

    public function title(): string
    {
        return 'Product Entries';
    }
}