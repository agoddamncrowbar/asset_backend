<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JwtService
{
    private static function config(): array
    {
        return require __DIR__ . '/../../config/jwt.php';
    }

    public static function generate(array $user): string
    {
        $config = self::config();

        $payload = [
            'iss' => $config['issuer'],
            'sub' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role'],

            'jti' => bin2hex(random_bytes(16)),

            'iat' => time(),
            'exp' => time() + $config['expiration']
        ];

        return JWT::encode(
            $payload,
            $config['secret'],
            'HS256'
        );
    }

    public static function validate(string $token): object
    {
        $config = self::config();

        return JWT::decode(
            $token,
            new Key($config['secret'], 'HS256')
        );
    }
    public static function getUserIdFromRequest(): int
    {
        $headers = getallheaders();

        if (!isset($headers['Authorization'])) {
            throw new Exception('Missing Authorization header');
        }

        $authHeader = $headers['Authorization'];

        if (!str_starts_with($authHeader, 'Bearer ')) {
            throw new Exception('Invalid Authorization header format');
        }

        $token = substr($authHeader, 7);

        $decoded = self::validate($token);

        return (int) $decoded->sub;
    }
    }