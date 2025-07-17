<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\User;
use App\Models\ProductVariant;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'variant_id',
        'warna',
        'ukuran',
        'jumlah',
        'total_harga',
        'status',
        'image',
        'payment_method',
        'bank_name',
        'wallet_type',
        'payment_proof'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
