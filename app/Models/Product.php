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
        'description',
        'image',
    ];
    public function productEntries()
    {
        return $this->hasMany(ProductEntry::class);
    }
    public function productExits()
    {
        return $this->hasMany(ProductExit::class);
    }
}
