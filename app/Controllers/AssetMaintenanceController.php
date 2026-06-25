<?php

namespace App\Controllers;

use App\Services\AssetMaintenanceService;

class AssetMaintenanceController
{
    public function index()
    {
        return AssetMaintenanceService::getAll();
    }

    public function show($id)
    {
        return AssetMaintenanceService::getById((int)$id);
    }

    public function store()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        return AssetMaintenanceService::create($data);
    }

    public function start($id)
    {
        return AssetMaintenanceService::start((int)$id);
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        return AssetMaintenanceService::update((int)$id, $data);
    }

    public function complete($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        return AssetMaintenanceService::complete((int)$id, $data);
    }
}