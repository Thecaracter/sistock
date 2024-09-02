<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductExitDetail extends Model
{
    use HasFactory;

    protected $table = 'product_exits_detail';

    protected $fillable = [
        'product_exit_id',
        'product_entry_detail_id',
        'quantity',
        'price',
        'total',
        'exit_date',
    ];

    /**
     * Get the product exit that owns the detail.
     */
    public function productExit()
    {
        return $this->belongsTo(ProductExit::class);
    }

    /**
     * Get the product entry detail that owns the exit detail.
     */
    public function productEntryDetail()
    {
        return $this->belongsTo(ProductEntryDetail::class, 'product_entry_detail_id');
    }

    /**
     * Decrement the stock of the associated product entry detail.
     */
    public function decrementStock()
    {
        $entryDetail = $this->productEntryDetail;

        if ($entryDetail) {
            // Log stok sebelum pengurangan
            \Log::info("Before Decrement - Entry ID: {$entryDetail->id}, Current Stock: {$entryDetail->stock}, Quantity: {$this->quantity}");

            // Mengurangi stok
            if ($entryDetail->stock >= $this->quantity) {
                $entryDetail->stock -= $this->quantity;
                $entryDetail->save();

                // Log stok setelah pengurangan
                \Log::info("After Decrement - Entry ID: {$entryDetail->id}, New Stock: {$entryDetail->stock}");
            } else {
                // Log jika stok tidak cukup
                \Log::warning("Not enough stock for Entry ID: {$entryDetail->id}. Current Stock: {$entryDetail->stock}, Attempted Quantity: {$this->quantity}");
            }
        }
    }
}

