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
        'product_entrie_detail_id',
        'quantity',
        'price',
        'total',
        'exit_date',
    ];

    /**
     * Relasi ke model ProductExit
     */
    public function productExit()
    {
        return $this->belongsTo(ProductExit::class);
    }

    /**
     * Relasi ke model ProductEntryDetail
     */
    public function productEntryDetail()
    {
        return $this->belongsTo(ProductEntryDetail::class);
    }
}
