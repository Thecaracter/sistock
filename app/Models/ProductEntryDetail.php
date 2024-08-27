<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductEntryDetail extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_entries_detail';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_entry_id',
        'product_id',
        'quantity',
        'price',
        'total',
    ];

    /**
     * Get the product entry that owns the detail.
     */
    public function productEntry()
    {
        return $this->belongsTo(ProductEntry::class);
    }

    /**
     * Get the product associated with the detail.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function productExitDetail()
    {
        return $this->hasMany(ProductExitDetail::class);
    }
}
