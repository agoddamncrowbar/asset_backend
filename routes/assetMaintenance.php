<?php

use App\Controllers\AssetMaintenanceController;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;
use App\Middleware\MaintenanceAccessMiddleware;
$adminOnly = [
    [AuthMiddleware::class],
    [RoleMiddleware::class, ['admin']]
];
$maintenanceAccess=[
    [AuthMiddleware::class],
    [MaintenanceAccessMiddleware::class]
];

$authenticated = [
    [AuthMiddleware::class]
];
// CRUD
$router->get('/api/maintenance', [AssetMaintenanceController::class, 'index'], $authenticated);

$router->get('/api/maintenance/{id}', [AssetMaintenanceController::class, 'show'],$maintenanceAccess);

$router->post('/api/maintenance', [AssetMaintenanceController::class, 'store'], $adminOnly);

$router->put('/api/maintenance/{id}', [AssetMaintenanceController::class, 'update'], $maintenanceAccess);

$router->get('/api/maintenance/search',[AssetMaintenanceController::class, 'search'], $adminOnly);

// Workflow
$router->post('/api/maintenance/{id}/start', [AssetMaintenanceController::class, 'start'], $maintenanceAccess);

$router->post('/api/maintenance/{id}/complete', [AssetMaintenanceController::class, 'complete'], $maintenanceAccess);