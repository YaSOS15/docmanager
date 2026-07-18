<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
        'theme',
        'view_mode',
        'storage_quota',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function suggestions(): HasMany
    {
        return $this->hasMany(Suggestion::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function usedStorage(): int
    {
        return (int) $this->documents()->sum('size');
    }

    public function storagePercentUsed(): float
    {
        if ($this->storage_quota <= 0) {
            return 0;
        }

        return min(100, round(($this->usedStorage() / $this->storage_quota) * 100, 1));
    }
}