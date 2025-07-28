<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;

class CartItem extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'variant_id',
        'warna',
        'ukuran',
        'harga_satuan',
        'jumlah',
    ];

    // Relasi ke User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Produk
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi ke Varian Produk
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
