<?php

use App\Controllers\DepartmentController;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;

$adminOnly = [
    [AuthMiddleware::class],
    [RoleMiddleware::class, ['admin']]
];


// Departments
$router->get('/api/departments', [DepartmentController::class, 'index'], $adminOnly);
$router->get('/api/departments/{id}', [DepartmentController::class, 'show'], $adminOnly);
$router->post('/api/departments', [DepartmentController::class, 'store'], $adminOnly);
$router->put('/api/departments/{id}', [DepartmentController::class, 'update'], $adminOnly);
$router->delete('/api/departments/{id}', [DepartmentController::class, 'destroy'], $adminOnly);

