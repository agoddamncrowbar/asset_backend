<?php

use App\Controllers\AssetMaintenanceController;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;

$adminOnly = [
    [AuthMiddleware::class],
    [RoleMiddleware::class, ['admin']]
];
// CRUD
$router->get('/api/maintenance', [AssetMaintenanceController::class, 'index'], $adminOnly);

$router->get('/api/maintenance/{id}', [AssetMaintenanceController::class, 'show'], $adminOnly);

$router->post('/api/maintenance', [AssetMaintenanceController::class, 'store'], $adminOnly);

$router->put('/api/maintenance/{id}', [AssetMaintenanceController::class, 'update'], $adminOnly);

// Workflow
$router->post('/api/maintenance/{id}/start', [AssetMaintenanceController::class, 'start'], $adminOnly);

$router->post('/api/maintenance/{id}/complete', [AssetMaintenanceController::class, 'complete'], $adminOnly);