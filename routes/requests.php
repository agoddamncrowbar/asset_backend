<?php

use App\Controllers\RequestController;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;

$auth = [
    [AuthMiddleware::class]
];

$adminOnly = [
    [AuthMiddleware::class],
    [RoleMiddleware::class, ['admin']]
];

// Requests (user actions)
$router->post('/api/requests', [RequestController::class, 'store'], $auth);
$router->delete('/api/requests/{id}', [RequestController::class, 'cancel'], $auth);
$router->get('/api/requests/asset/{assetId}/queue', [RequestController::class, 'queue'], $auth);
$router->get('/api/requests/asset/{assetId}/next', [RequestController::class, 'next'], $auth);

// Requests (admin actions)
$router->post('/api/requests/{id}/approve', [RequestController::class, 'approve'], $adminOnly);
$router->post('/api/requests/{id}/reject', [RequestController::class, 'reject'], $adminOnly);