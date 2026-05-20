<?php

namespace App\Core\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super-admin';
    case ADMIN = 'admin';
    case EDITOR = 'editor';
    case PRO = 'pro';
    case USER = 'user';

    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Admin',
            self::ADMIN => 'Administrator',
            self::EDITOR => 'Editor',
            self::PRO => 'Pro User',
            self::USER => 'Standard User',
        };
    }

    public function level(): int
    {
        return match ($this) {
            self::SUPER_ADMIN => 100,
            self::ADMIN => 80,
            self::EDITOR => 60,
            self::PRO => 40,
            self::USER => 20,
        };
    }
}
