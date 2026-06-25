<?php

use App\Controllers\UserController;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;

$adminOnly = [
    [AuthMiddleware::class],
    [RoleMiddleware::class, ['admin']]
];

$router->get('/api/users', [UserController::class, 'index'], $adminOnly);

$router->get('/api/users/search', [UserController::class, 'search'], $adminOnly);

$router->get('/api/users/{id}', [UserController::class, 'show'], $adminOnly);

$router->get('/api/users/{id}/asset-status', [UserController::class, 'assetStatus'], $adminOnly);

$router->get('/api/users/{id}/eligibility', [UserController::class, 'eligibility'], $adminOnly);

$router->post('/api/users', [UserController::class, 'store'], $adminOnly);

$router->put('/api/users/{id}', [UserController::class, 'update'], $adminOnly);

$router->patch('/api/users/{id}/suspend', [UserController::class, 'suspend'], $adminOnly);

$router->patch('/api/users/{id}/activate', [UserController::class, 'activate'], $adminOnly);

$router->delete('/api/users/{id}', [UserController::class, 'destroy'], $adminOnly);