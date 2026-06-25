<?php

namespace App\Core;

use App\Helpers\Auth;

class AuditContext
{
    public static function getUserId(): ?int
    {
        $user = Auth::user();

        return $user['id'] ?? null;
    }

    public static function getIpAddress(): ?string
    {
        return $_SERVER['REMOTE_ADDR'] ?? null;
    }

    public static function getUserAgent(): ?string
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? null;
    }

    public static function getContext(): array
    {
        return [
            'user_id' => self::getUserId(),
            'ip_address' => self::getIpAddress(),
            'user_agent' => self::getUserAgent()
        ];
    }
}