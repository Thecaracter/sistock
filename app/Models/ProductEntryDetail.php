<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductEntryDetail extends Model
{
    use HasFactory;

    protected $table = 'product_entries_detail';

    protected $fillable = [
        'product_entry_id',
        'product_id',
        'quantity',
        'price',
        'total',
        'stock', // pastikan stock ada di fillable
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->stock = $model->quantity; // Set stock ke nilai quantity
        });
    }

    public function productEntry()
    {
        return $this->belongsTo(ProductEntry::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productExitDetail()
    {
        return $this->hasMany(ProductExitDetail::class);
    }
}
