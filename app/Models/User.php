<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Core\Enums\UserRole;

#[Fillable(['name', 'email', 'password', 'role', 'quota_limit'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === UserRole::SUPER_ADMIN;
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN || $this->isSuperAdmin();
    }

    public function isEditor(): bool
    {
        return $this->role === UserRole::EDITOR || $this->isAdmin();
    }

    public function isPro(): bool
    {
        return $this->role === UserRole::PRO || $this->isEditor();
    }

    public function hasRole(UserRole $role): bool
    {
        return $this->role->level() >= $role->level();
    }
}
