<?php

use App\Controllers\LocationController;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;

$adminOnly = [
    [AuthMiddleware::class],
    [RoleMiddleware::class, ['admin']]
];

// Locations
$router->get('/api/locations', [LocationController::class, 'index'], $adminOnly);
$router->get('/api/locations/{id}', [LocationController::class, 'show'], $adminOnly);
$router->post('/api/locations', [LocationController::class, 'store'], $adminOnly);
$router->put('/api/locations/{id}', [LocationController::class, 'update'], $adminOnly);
$router->delete('/api/locations/{id}', [LocationController::class, 'destroy'], $adminOnly);