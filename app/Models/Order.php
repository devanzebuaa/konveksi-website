<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\User;
use App\Models\ProductVariant;
use App\Models\OrderItem;
use App\Models\ProductSizePrice;

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
        'payment_proof',
        'address',
        'invoice_path',
        'user_notified',
    ];

    protected $appends = ['status_badge'];

    protected $casts = [
        'status_badge'   => 'string',
        'user_notified'  => 'boolean',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi ke varian produk
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    // Jika order bisa punya banyak item
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ✅ Relasi ke harga ukuran berdasarkan product_id dan ukuran SAJA
    public function sizePrice()
    {
        return $this->hasOne(ProductSizePrice::class, 'product_id', 'product_id')
                    ->where('size', $this->ukuran);
    }

    // Badge status untuk tampilan
    public function getStatusBadgeAttribute()
    {
        $class = match ($this->status) {
            'Menunggu Pembayaran' => 'bg-warning text-dark',
            'Sudah Dibayar'       => 'bg-success',
            'Diproses'            => 'bg-primary',
            'Dikirim'             => 'bg-info text-dark',
            'Selesai'             => 'bg-secondary',
            'Dibatalkan'          => 'bg-danger',
            default               => 'bg-light text-dark',
        };

        return "<span class=\"badge rounded-pill {$class}\">{$this->status}</span>";
    }
}
