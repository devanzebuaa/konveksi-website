<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'product_type',
        'size_detail',
        'description',
        'design_file',
        'quantity',
        'payment_method',
        'contact',
    ];
}
