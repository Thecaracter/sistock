<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductExit extends Model
{
    use HasFactory;
    protected $table = 'product_exits';

    protected $fillable = [
        'product_id',
        'quantity',
        'price',
        'total',
        'exit_date',
    ];

    /**
     * Relationship with Product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
