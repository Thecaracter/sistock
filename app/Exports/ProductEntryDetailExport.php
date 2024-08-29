<?php

namespace App\Exports;

use App\Models\ProductEntryDetail;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductEntryDetailExport implements FromQuery, WithHeadings
{
    protected $productEntryId;

    public function __construct($productEntryId)
    {
        $this->productEntryId = $productEntryId;
    }

    public function query()
    {
        return ProductEntryDetail::query()
            ->where('product_entry_id', $this->productEntryId)
            ->select('id', 'product_id', 'quantity', 'price', 'total');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Product ID',
            'Quantity',
            'Price',
            'Total',
        ];
    }
}
