<?php

use App\Controllers\AssetInspectionController;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;

$adminOnly = [
    [AuthMiddleware::class],
    [RoleMiddleware::class, ['admin']]
];

// Inspection CRUD
$router->get('/api/inspections', [AssetInspectionController::class, 'index'], $adminOnly);

$router->get('/api/inspections/{id}', [AssetInspectionController::class, 'show'], $adminOnly);

$router->post('/api/inspections', [AssetInspectionController::class, 'create'], $adminOnly);

$router->delete('/api/inspections/{id}', [AssetInspectionController::class, 'delete'], $adminOnly);

// Workflow actions
$router->post('/api/inspections/{id}/start', [AssetInspectionController::class, 'start'], $adminOnly);

$router->post('/api/inspections/{id}/complete', [AssetInspectionController::class, 'complete'], $adminOnly);

// Per-asset inspection result
$router->post('/api/inspections/{inspectionId}/assets/{assetId}/result',[AssetInspectionController::class, 'recordResult'], $adminOnly);

$router->get('/api/inspections/{id}/assets', [AssetInspectionController::class, 'assetIds']);