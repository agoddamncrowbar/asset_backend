<?php

namespace App\Controllers;

use App\Services\AssetService;
use App\Services\AuditService;
use App\Services\JwtService;
use Exception;

class AssetController
{
    private AssetService $service;

    public function __construct()
    {
        $this->service = new AssetService();
    }

    private function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function index(): void
    {
        try {
            $limit = $_GET['limit'] ?? 50;
            $offset = $_GET['offset'] ?? 0;

            $assets = $this->service->getAllAssets((int)$limit, (int)$offset);

            $this->json([
                'success' => true,
                'data' => $assets
            ]);
        } catch (Exception $e) {
            $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id): void
    {
        try {
            $asset = $this->service->getAssetById($id);

            if (!$asset) {
                $this->json([
                    'success' => false,
                    'message' => 'Asset not found'
                ], 404);
            }

            $this->json([
                'success' => true,
                'data' => $asset
            ]);
        } catch (Exception $e) {
            $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(): void
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true);

            if (!$input) {
                $this->json([
                    'success' => false,
                    'message' => 'Invalid JSON body'
                ], 400);
            }

            $userId = JwtService::getUserIdFromRequest();

            $created = $this->service->createAsset($input, $userId);

            AuditService::logCreate(
                'Asset',
                $created['id'],
                $created
            );

            $this->json([
                'success' => true,
                'message' => 'Asset created',
                'data' => $created
            ], 201);

        } catch (Exception $e) {
            $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(int $id): void
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true);

            if (!$input) {
                $this->json([
                    'success' => false,
                    'message' => 'Invalid JSON body'
                ], 400);
            }

            $result = $this->service->updateAsset($id, $input);

            AuditService::logUpdate(
                'Asset',
                $id,
                $result['old'],
                $result['new']
            );

            $this->json([
                'success' => true,
                'message' => 'Asset updated',
                'data' => $result['new']
            ]);

        } catch (Exception $e) {
            $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function retire(int $id): void
    {
        try {
            $result = $this->service->retireAsset($id);

            AuditService::logUpdate(
                'Asset',
                $id,
                $result['old'],
                $result['new']
            );

            $this->json([
                'success' => true,
                'message' => 'Asset retired',
                'data' => $result['new']
            ]);

        } catch (Exception $e) {
            $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}