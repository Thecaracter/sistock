<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductEntry extends Model
{
    use HasFactory;
    protected $table = 'product_entries';

    protected $fillable = [
        'nama_kapal',
        'no_permintaan',
        'tgl_permintaan',
        'jenis_barang',
        'total',
    ];
    public function productEntryDetail()
    {
        return $this->hasMany(ProductEntryDetail::class);
    }
}
