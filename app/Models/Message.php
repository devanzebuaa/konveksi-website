<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['order_id', 'sender_id', 'sender_type', 'content'];

    public function order()
    {
        return $this->belongsTo(\App\Models\CustomOrder::class, 'order_id');
    }

    public function sender()
    {
        return $this->belongsTo(\App\Models\User::class, 'sender_id');
    }
}

