<?php

namespace App\Imports;

use App\Models\ProductEntry;
use App\Models\ProductEntryDetail;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

// Import classes (unchanged)
class ProductEntriesImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            0 => new ProductEntriesSheet(),
            1 => new AllProductEntryDetailsSheet(),
        ];
    }
}

class ProductEntriesSheet implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new ProductEntry([
            'nama_kapal' => $row['nama_kapal'] ?? null,
            'no_permintaan' => $row['no_permintaan'] ?? null,
            'tgl_permintaan' => Carbon::parse($row['tgl_permintaan'] ?? null),
            'jenis_barang' => $row['jenis_barang'] ?? null,
            'total' => $row['total'] ?? 0,
        ]);
    }
}

class AllProductEntryDetailsSheet implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new ProductEntryDetail([
            'product_entry_id' => $row['product_entry_id'] ?? null,
            'product_id' => $row['product_id'] ?? null,
            'quantity' => $row['quantity'] ?? 0,
            'price' => $row['price'] ?? 0,
            'total' => $row['total'] ?? 0,
            'stock' => $row['stock'] ?? 0,
        ]);
    }
}

// Export classes (added to the same file)
class CombinedProductExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        return [
            new ProductEntriesExport(),
            new AllProductEntryDetailsExport(),
        ];
    }
}

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
            $detail->product->name ?? 'N/A',
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