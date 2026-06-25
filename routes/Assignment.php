<?php

use App\Controllers\AssignmentController;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;
$adminOnly = [
    [AuthMiddleware::class],
    [RoleMiddleware::class, ['admin']]
];
$router->get( '/api/assignments', [AssignmentController::class, 'index'], $adminOnly );

$router->get( '/api/assignments/{id}', [AssignmentController::class, 'show'], $adminOnly );

$router->post( '/api/assignments', [AssignmentController::class, 'store'], $adminOnly );  

$router->post( '/api/assignments/{id}/return', [AssignmentController::class, 'returnAsset'], $adminOnly );

$router->get( '/api/assets/{id}/assignment', [AssignmentController::class, 'activeByAsset'], $adminOnly );

$router->get( '/api/assets/{id}/assignments', [AssignmentController::class, 'assetHistory'], $adminOnly );

$router->get( '/api/users/{id}/assignments', [AssignmentController::class, 'userAssignments'], $adminOnly );