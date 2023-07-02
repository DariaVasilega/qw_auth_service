<?php

declare(strict_types=1);

namespace App\Domain\User;

enum Status: string
{
    case ACTIVE = '1';
    case INACTIVE = '0';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function label(self $status): string
    {
        return match ($status) {
            self::ACTIVE => 'user.status.active',
            self::INACTIVE => 'user.status.inactive',
        };
    }
}
