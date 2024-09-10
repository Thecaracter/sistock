<?php

namespace App\Exports;

use App\Models\ProductExit;
use App\Models\ProductExitDetail;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductExitExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        $sheets = [
            new ProductExitSheet(),
        ];

        $productExits = ProductExit::all();
        foreach ($productExits as $exit) {
            $sheets[] = new ProductExitDetailSheet($exit->id);
        }

        return $sheets;
    }
}

class ProductExitSheet implements FromQuery, WithTitle, WithHeadings
{
    public function query()
    {
        return ProductExit::query()->select('id', 'nama_kapal', 'no_exit', 'tgl_exit', 'jenis_barang', 'total');
    }

    public function title(): string
    {
        return 'Product Exits';
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Kapal',
            'No Exit',
            'Tanggal Exit',
            'Jenis Barang',
            'Total'
        ];
    }
}

class ProductExitDetailSheet implements FromQuery, WithTitle, WithHeadings
{
    protected $productExitId;

    public function __construct($productExitId)
    {
        $this->productExitId = $productExitId;
    }

    public function query()
    {
        return ProductExitDetail::query()
            ->where('product_exit_id', $this->productExitId)
            ->select('id', 'product_exit_id', 'product_entry_detail_id', 'quantity', 'price', 'total', 'exit_date');
    }

    public function title(): string
    {
        return 'Exit Details - ' . $this->productExitId;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Product Exit ID',
            'Product Entry Detail ID',
            'Quantity',
            'Price',
            'Total',
            'Exit Date',
        ];
    }
}