<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'color',
        'image',
        'stock',
        'price',
        'is_featured',
    ];

    // Relasi ke produk induknya
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // ✅ Hapus relasi sizePrices() karena tabel product_size_prices tidak memiliki kolom variant_id
}
