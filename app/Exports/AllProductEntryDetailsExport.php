<?php

namespace App\Exports;

use App\Models\ProductEntryDetail;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

class AllProductEntryDetailsExport implements FromQuery, WithHeadings, WithTitle, WithMapping
{
    public function query()
    {
        return ProductEntryDetail::query();
    }

    public function map($detail): array
    {
        return [
            $detail->id,
            $detail->product_entry_id,
            $detail->product_id,
            $detail->product->name ?? 'N/A', // Assuming Product model has a 'name' field
            $detail->quantity,
            $detail->price,
            $detail->total,
            $detail->stock,
            $detail->created_at,
            $detail->updated_at,
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Product Entry ID',
            'Product ID',
            'Product Name',
            'Quantity',
            'Price',
            'Total',
            'Stock',
            'Created At',
            'Updated At',
        ];
    }

    public function title(): string
    {
        return 'Product Entry Details';
    }
}