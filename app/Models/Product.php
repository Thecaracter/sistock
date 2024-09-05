<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'product';
    protected $fillable = [
        'name',
        'part_code',
        'merk',
        'image',
        'code_barang',
    ];
    public function productEntriesDetail()
    {
        return $this->hasMany(ProductEntryDetail::class);
    }

}
