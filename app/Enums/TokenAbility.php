<?php

namespace App\Enums;

enum TokenAbility: string
{
    case POST_READ = 'post:read';
    case POST_CREATE = 'post:create';
    case POST_UPDATE = 'post:update';
    case POST_DELETE = 'post:delete';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function description(): string
    {
        return match ($this) {
            self::POST_READ => 'Permission to read posts',
            self::POST_CREATE => 'Permission to create posts',
            self::POST_UPDATE => 'Permission to update posts',
            self::POST_DELETE => 'Permission to delete posts',
        };
    }
}
