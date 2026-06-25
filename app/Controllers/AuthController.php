<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\JwtService;
use App\Helpers\Auth;
use App\Models\RevokedToken;
use App\Middleware\AuthMiddleware;
class AuthController
{
    public function login(): void
    {
        $body = json_decode(
            file_get_contents('php://input'),
            true
        );

        $email = trim($body['email'] ?? '');
        $password = $body['password'] ?? '';

        if (empty($email) || empty($password))
        {
            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => 'Email and password are required'
            ]);

            return;
        }

        $user = User::findByEmail($email);

        if (!$user)
        {
            http_response_code(401);

            echo json_encode([
                'success' => false,
                'message' => 'Invalid credentials'
            ]);

            return;
        }

        if (!password_verify(
            $password,
            $user['password_hash']
        ))
        {
            http_response_code(401);

            echo json_encode([
                'success' => false,
                'message' => 'Invalid credentials'
            ]);

            return;
        }

        if ($user['status'] !== 'active')
        {
            http_response_code(403);

            echo json_encode([
                'success' => false,
                'message' => 'Account is not active'
            ]);

            return;
        }

        $token = JwtService::generate($user);

        unset($user['password_hash']);

        echo json_encode([
            'success' => true,
            'token' => $token,
            'user' => $user
        ]);
    }


    public function logout(): void
    {
        AuthMiddleware::handle();

        $payload = Auth::payload();

        RevokedToken::revoke(
            $payload->jti,
            $payload->exp
        );

        echo json_encode([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    public function changePassword(): void
    {
        $body = json_decode(
            file_get_contents('php://input'),
            true
        );

        $currentPassword =
            $body['current_password'] ?? '';

        $newPassword =
            $body['new_password'] ?? '';

        if (
            empty($currentPassword) ||
            empty($newPassword)
        )
        {
            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' =>
                    'Current password and new password are required'
            ]);

            return;
        }

        if (strlen($newPassword) < 8)
        {
            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' =>
                    'New password must be at least 8 characters'
            ]);

            return;
        }

        $user = Auth::user();

        if (!$user)
        {
            http_response_code(401);

            echo json_encode([
                'success' => false,
                'message' => 'Unauthorized'
            ]);

            return;
        }

        $dbUser = User::findById(
            $user['id']
        );

        if (
            !password_verify(
                $currentPassword,
                $dbUser['password_hash']
            )
        )
        {
            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => 'Current password is incorrect'
            ]);

            return;
        }

        if (
            password_verify(
                $newPassword,
                $dbUser['password_hash']
            )
        )
        {
            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' =>
                    'New password must be different from the current password'
            ]);

            return;
        }

        User::updatePassword(
            $user['id'],
            password_hash(
                $newPassword,
                PASSWORD_DEFAULT
            )
        );

        echo json_encode([
            'success' => true,
            'message' => 'Password updated successfully'
        ]);
    }
}