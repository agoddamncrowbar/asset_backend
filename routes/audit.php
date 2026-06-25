<?php

use App\Controllers\AuditController;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;

$adminOnly = [
    [AuthMiddleware::class],
    [RoleMiddleware::class, ['admin']]
];


$router->get( '/api/audit-logs', [AuditController::class, 'index'], $adminOnly );

$router->get( '/api/audit-logs/{id}', [AuditController::class, 'show'], $adminOnly );

$router->get( '/api/audit-log-summaries', [AuditController::class, 'summaries'], $adminOnly );

$router->get( '/api/audit-log-summaries/{id}', [AuditController::class, 'summaryById'], $adminOnly );