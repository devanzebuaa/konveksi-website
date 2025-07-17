<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
    ];

    // Accessor untuk nama
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucwords($value),
            set: fn (string $value) => strtolower($value),
        );
    }

    // Accessor untuk avatar URL
    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->avatar
                ? asset('storage/avatars/' . $this->avatar)
                : asset('images/default-avatar.png'),
        );
    }

    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }
}
