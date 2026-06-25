<?php

namespace App\Helpers;

class Auth
{
    private static ?array $user = null;

    private static ?object $payload = null;

    public static function setUser(array $user): void
    {
        self::$user = $user;
    }

    public static function user(): ?array
    {
        return self::$user;
    }

    public static function id(): ?int
    {
        return self::$user['id'] ?? null;
    }

    public static function check(): bool
    {
        return self::$user !== null;
    }

    public static function setPayload(object $payload): void
    {
        self::$payload = $payload;
    }

    public static function payload(): ?object
    {
        return self::$payload;
    }
}