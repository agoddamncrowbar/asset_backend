<?php

namespace App\Controllers;

use App\Models\AssetLocation;
use App\Services\AuditService;

class LocationController
{
    private function json(array $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json');

        echo json_encode($data);
        exit;
    }

    public function index(): void
    {
        $this->json([
            'success' => true,
            'data' => AssetLocation::getAll()
        ]);
    }

    public function show(int $id): void
    {
        $location = AssetLocation::findById($id);

        if (!$location) {
            $this->json([
                'success' => false,
                'message' => 'Location not found'
            ], 404);
        }

        $this->json([
            'success' => true,
            'data' => $location
        ]);
    }

    public function store(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            $this->json([
                'success' => false,
                'message' => 'Invalid JSON body'
            ], 400);
        }

        if (empty($input['name'])) {
            $this->json([
                'success' => false,
                'message' => 'Missing field: name'
            ], 422);
        }

        $created = AssetLocation::create([
            'name' => $input['name'],
            'building' => $input['building'] ?? null,
            'room_number' => $input['room_number'] ?? null,
            'description' => $input['description'] ?? null
        ]);

        $id = $created['id'];

        AuditService::logCreate(
            'AssetLocation',
            $id,
            $created
        );

        $this->json([
            'success' => true,
            'message' => 'Location created',
            'data' => $created
        ], 201);
    }

    public function update(int $id): void
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            $this->json([
                'success' => false,
                'message' => 'Invalid JSON body'
            ], 400);
        }

        $old = AssetLocation::findById($id);

        if (!$old) {
            $this->json([
                'success' => false,
                'message' => 'Location not found'
            ], 404);
        }

        $updated = AssetLocation::update($id, $input);

        if (!$updated) {
            $this->json([
                'success' => false,
                'message' => 'Failed to update location'
            ], 500);
        }

        AuditService::logUpdate(
            'AssetLocation',
            $id,
            $old,
            $updated
        );

        $this->json([
            'success' => true,
            'message' => 'Location updated',
            'data' => $updated
        ]);
    }

    public function destroy(int $id): void
    {
        $old = AssetLocation::findById($id);

        if (!$old) {
            $this->json([
                'success' => false,
                'message' => 'Location not found'
            ], 404);
        }

        $ok = AssetLocation::delete($id);

        if (!$ok) {
            $this->json([
                'success' => false,
                'message' => 'Failed to delete location'
            ], 500);
        }

        AuditService::logDelete(
            'AssetLocation',
            $id,
            $old
        );

        $this->json([
            'success' => true,
            'message' => 'Location deleted'
        ]);
    }
}