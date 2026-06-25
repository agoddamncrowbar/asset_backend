<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header('Content-Type: application/json');

require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Router;

$router = new Router();
$router->setBasePath('/asset_backend');


require_once __DIR__ . '/routes/auth.php';
require_once __DIR__ . '/routes/users.php';
require_once __DIR__ . '/routes/audit.php';
require_once __DIR__ . '/routes/departments.php';
require_once __DIR__ . '/routes/assetCategories.php';
require_once __DIR__ . '/routes/AssetLocation.php';
require_once __DIR__ . '/routes/assets.php';
require_once __DIR__ . '/routes/Assignment.php';
require_once __DIR__ . '/routes/requests.php';
require_once __DIR__ . '/routes/asset_inspections.php';
require_once __DIR__ . '/routes/assetMaintenance.php';

$uri = str_replace(
    '/asset_backend',
    '',
    $_SERVER['REQUEST_URI']
);

$router->dispatch(
    $uri,
    $_SERVER['REQUEST_METHOD']
);