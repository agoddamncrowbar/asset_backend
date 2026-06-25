<?php

use App\Controllers\AuthController;
use App\Middleware\AuthMiddleware;

$router->post(
    '/api/auth/login',
    [AuthController::class, 'login']
);

$router->get('/api/auth/me', function ()
{
    \App\Middleware\AuthMiddleware::handle();

    echo json_encode([
        'success' => true,
        'user' => \App\Helpers\Auth::user()
    ]);
});

$router->post(
    '/api/auth/logout',
    [AuthController::class, 'logout']
);

$router->post(
    '/api/auth/change-password',
    function () {
        \App\Middleware\AuthMiddleware::handle();

        (new \App\Controllers\AuthController())
            ->changePassword();
    }
);