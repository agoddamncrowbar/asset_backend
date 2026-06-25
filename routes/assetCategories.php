<?php

use App\Controllers\AssetCategoryController;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;

$adminOnly = [
    [AuthMiddleware::class],
    [RoleMiddleware::class, ['admin']]
];




// Categories
$router->get('/api/categories', [AssetCategoryController::class, 'index'], $adminOnly);
$router->get('/api/categories/{id}', [AssetCategoryController::class, 'show'], $adminOnly);
$router->post('/api/categories', [AssetCategoryController::class, 'store'], $adminOnly);
$router->put('/api/categories/{id}', [AssetCategoryController::class, 'update'], $adminOnly);
$router->delete('/api/categories/{id}', [AssetCategoryController::class, 'destroy'], $adminOnly);