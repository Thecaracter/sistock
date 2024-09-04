<?php

namespace App\Exports;

use App\Models\ProductExitDetail;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductExitDetailsExport implements FromQuery, WithHeadings
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
            ->select('id', 'product_entry_detail_id', 'quantity', 'price', 'total', 'exit_date');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Product Entry Detail ID',
            'Quantity',
            'Price',
            'Total',
            'Exit Date',
        ];
    }
}