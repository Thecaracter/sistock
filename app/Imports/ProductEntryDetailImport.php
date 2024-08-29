<?php

namespace App\Imports;

use App\Models\ProductEntryDetail;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductEntryDetailImport implements ToModel, WithHeadingRow
{
    protected $productEntryId;

    public function __construct($productEntryId)
    {
        $this->productEntryId = $productEntryId;
    }

    public function model(array $row)
    {
        return new ProductEntryDetail([
            'product_entry_id' => $this->productEntryId,
            'product_id' => $row['product_id'],
            'quantity' => $row['quantity'],
            'price' => $row['price'],
            'total' => $row['total'],
        ]);
    }
}

