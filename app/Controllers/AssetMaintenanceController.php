<?php

namespace App\Controllers;

use App\Services\AssetMaintenanceService;

class AssetMaintenanceController
{
    public function index()
    {
        header('Content-Type: application/json');

        echo json_encode([
            'success' => true,
            'data' => AssetMaintenanceService::getAll()
        ]);
    }

    public function show($id)
    {
        header('Content-Type: application/json');

        echo json_encode([
            'success' => true,
            'data' => AssetMaintenanceService::getById((int)$id)
        ]);
    }

    public function store()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $job = AssetMaintenanceService::create($data);

        http_response_code(201);
        header('Content-Type: application/json');

        echo json_encode([
            'success' => true,
            'message' => 'Maintenance job created successfully.',
            'data' => $job
        ]);
    }

    public function start($id)
    {
        $job = AssetMaintenanceService::start((int)$id);

        header('Content-Type: application/json');

        echo json_encode([
            'success' => true,
            'message' => 'Maintenance job started successfully.',
            'data' => $job
        ]);
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $job = AssetMaintenanceService::update((int)$id, $data);

        header('Content-Type: application/json');

        echo json_encode([
            'success' => true,
            'message' => 'Maintenance job updated successfully.',
            'data' => $job
        ]);
    }

    public function complete($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $job = AssetMaintenanceService::complete((int)$id, $data);

        header('Content-Type: application/json');

        echo json_encode([
            'success' => true,
            'message' => 'Maintenance job completed successfully.',
            'data' => $job
        ]);
    }
}