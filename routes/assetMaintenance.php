<?php

use App\Controllers\AssetMaintenanceController;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;

$adminOnly = [
    [AuthMiddleware::class],
    [RoleMiddleware::class, ['admin']]
];
// CRUD
$router->get('/api/maintenance', [AssetMaintenanceController::class, 'index']);

$router->get('/api/maintenance/{id}', [AssetMaintenanceController::class, 'show']);

$router->post('/api/maintenance', [AssetMaintenanceController::class, 'store'], $adminOnly);

$router->put('/api/maintenance/{id}', [AssetMaintenanceController::class, 'update']);

// Workflow
$router->post('/api/maintenance/{id}/start', [AssetMaintenanceController::class, 'start']);

$router->post('/api/maintenance/{id}/complete', [AssetMaintenanceController::class, 'complete']);