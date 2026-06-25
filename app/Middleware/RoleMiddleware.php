<?php

namespace App\Middleware;

use App\Helpers\Auth;

class RoleMiddleware
{
    public static function handle(
        array $allowedRoles
    ): void
    {
        $user = Auth::user();

        if (!$user)
        {
            http_response_code(401);

            echo json_encode([
                'success' => false,
                'message' => 'Unauthenticated'
            ]);

            exit;
        }

        if (
            !in_array(
                $user['role'],
                $allowedRoles
            )
        )
        {
            http_response_code(403);

            echo json_encode([
                'success' => false,
                'message' => 'Forbidden'
            ]);

            exit;
        }
    }
}