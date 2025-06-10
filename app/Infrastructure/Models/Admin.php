<?php

namespace App\Infrastructure\Models;

use Database\Factories\AdminFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $name
 * @property string $email
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 *
 * @method static \Database\Factories\AdminFactory factory($count = null, $state = [])
 */
class Admin extends Authenticatable
{
    use HasApiTokens;
    /** @use HasFactory<\Database\Factories\AdminFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): AdminFactory
    {
        return AdminFactory::new();
    }

    /**
     * @return HasMany<\App\Infrastructure\Models\Profile, $this>
     */
    public function profiles(): HasMany
    {
        return $this->hasMany(\App\Infrastructure\Models\Profile::class);
    }

    /**
     * @return HasMany<\App\Infrastructure\Models\Comment, $this>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(\App\Infrastructure\Models\Comment::class);
    }
}
