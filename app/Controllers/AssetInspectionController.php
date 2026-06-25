<?php

namespace App\Controllers;

use App\Services\AssetInspectionService;

class AssetInspectionController
{
    public function index()
    {
        return AssetInspectionService::getAll();
    }

    public function show($id)
    {
        return AssetInspectionService::getById($id);
    }

    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        return AssetInspectionService::create($data);
    }

    public function start($id)
    {
        return AssetInspectionService::start((int)$id);
    }

    public function recordResult($inspectionId, $assetId)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        return AssetInspectionService::recordResult(
            (int)$inspectionId,
            (int)$assetId,
            $data
        );
    }

    public function complete($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        return AssetInspectionService::complete(
            (int)$id,
            $data['user_id']
        );
    }
}