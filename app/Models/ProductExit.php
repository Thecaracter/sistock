<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductExit extends Model
{
    use HasFactory;

    protected $table = 'product_exits';

    protected $fillable = [
        'nama_kapal',
        'no_exit',
        'tgl_exit',
        'jenis_barang',
        'total',
    ];

    /**
     * Get the details for the product exit.
     */
    public function productExitDetails()
    {
        return $this->hasMany(ProductExitDetail::class);
    }
}
