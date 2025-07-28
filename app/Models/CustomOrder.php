<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'product_type',
        'size_detail',
        'description',
        'design_file',
        'quantity',
        'contact',
    ];

    public function messages()
    {
        return $this->hasMany(\App\Models\Message::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
