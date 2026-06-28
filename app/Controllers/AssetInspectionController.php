<?php

namespace App\Controllers;

use App\Services\AssetInspectionService;
use App\Models\InspectionAssets;

class AssetInspectionController
{
    public function index()
    {
        header('Content-Type: application/json');

        echo json_encode([
            "success" => true,
            "data" => AssetInspectionService::getAll()
        ]);
    }

    public function show($id)
    {
        header('Content-Type: application/json');

        echo json_encode([
            "success" => true,
            "data" => AssetInspectionService::getById($id)
        ]);
    }

    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $inspection = AssetInspectionService::create($data);

        http_response_code(201);
        header('Content-Type: application/json');

        echo json_encode([
            "success" => true,
            "message" => "Inspection created successfully.",
            "data" => $inspection
        ]);
    }

    public function start($id)
    {
        $inspection = AssetInspectionService::start((int)$id);

        header('Content-Type: application/json');

        echo json_encode([
            "success" => true,
            "message" => "Inspection started successfully.",
            "data" => $inspection
        ]);
    }

    public function recordResult($inspectionId, $assetId)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $result = AssetInspectionService::recordResult(
            (int)$inspectionId,
            (int)$assetId,
            $data
        );

        header('Content-Type: application/json');

        echo json_encode([
            "success" => true,
            "message" => "Inspection result recorded successfully.",
            "data" => $result
        ]);
    }

    public function complete($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $inspection = AssetInspectionService::complete(
            (int)$id,
            $data['user_id']
        );

        header('Content-Type: application/json');

        echo json_encode([
            "success" => true,
            "message" => "Inspection completed successfully.",
            "data" => $inspection
        ]);
    }
    public function assetIds($inspectionId)
    {
        header('Content-Type: application/json');

        echo json_encode([
            "success" => true,
            "data" => InspectionAssets::getAssetIdsByInspection((int)$inspectionId)
        ]);
    }
}