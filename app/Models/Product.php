<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'image',
        'price',
        'is_featured',
    ];

    // Relasi ke tabel product_variants
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
