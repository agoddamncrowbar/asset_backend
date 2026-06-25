<?php

namespace App\Middleware;

use App\Helpers\Auth;
use App\Models\User;
use App\Services\JwtService;
use App\Models\RevokedToken;
use Exception;

class AuthMiddleware
{
    public static function handle(): void
    {
        $headers = getallheaders();

        $authorization =
            $headers['Authorization']
            ?? $headers['authorization']
            ?? null;

        if (!$authorization)
        {
            self::unauthorized(
                'Authorization token missing'
            );
        }

        if (!preg_match(
            '/Bearer\s+(.*)$/i',
            $authorization,
            $matches
        ))
        {
            self::unauthorized(
                'Invalid authorization header'
            );
        }

        $token = $matches[1];

        try
        {
            $payload = JwtService::validate($token);
            Auth::setPayload($payload);
            if (
                RevokedToken::isRevoked(
                    $payload->jti
                )
            )
            {
                self::unauthorized(
                    'Token has been revoked'
                );
            }

            $user = User::findById(
                $payload->sub
            );

            if (!$user)
            {
                self::unauthorized(
                    'User not found'
                );
            }

            if ($user['status'] !== 'active')
            {
                self::unauthorized(
                    'Account inactive'
                );
            }

            unset($user['password_hash']);

            Auth::setUser($user);
        }
        catch (Exception $e)
        {
            self::unauthorized(
                'Invalid or expired token'
            );
        }
    }

    private static function unauthorized(
        string $message
    ): void
    {
        http_response_code(401);

        echo json_encode([
            'success' => false,
            'message' => $message
        ]);

        exit;
    }
    
}