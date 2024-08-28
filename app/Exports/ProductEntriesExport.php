<?php

namespace App\Exports;

use App\Models\ProductEntry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Log;

class ProductEntriesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $data = ProductEntry::select('id', 'nama_kapal', 'no_permintaan', 'tgl_permintaan', 'jenis_barang', 'total')
            ->get()
            ->map(function ($item) {
                $item->total = $item->total ?? 0;
                Log::info('Exporting item:', $item->toArray()); // Tambahkan logging
                return $item;
            });

        Log::info('Total items to export: ' . $data->count());

        return $data;
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
        ];
    }
}