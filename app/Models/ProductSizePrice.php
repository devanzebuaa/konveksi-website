<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSizePrice extends Model
{
    use HasFactory;

    protected $table = 'product_size_prices';

    protected $fillable = [
        'product_id',
        'size',
        'price',
    ];

    /**
     * Relasi ke Produk
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // ❌ relasi variant dihapus karena kamu tidak pakai variant_id
}
