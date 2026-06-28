<?php

use App\Controllers\AssetController;
use App\Controllers\AssetSearchController;

$router->get( '/api/assets', [AssetController::class, 'index'] );

$router->get( '/api/assets/search', [AssetSearchController::class, 'search'] );

$router->get( '/api/assets/{id}', [AssetController::class, 'show'] );

$router->post('/api/assets', [AssetController::class, 'store']);

$router->put( '/api/assets/{id}', [AssetController::class, 'update'] );

$router->post( '/api/assets/{id}/retire', [AssetController::class, 'retire'] );