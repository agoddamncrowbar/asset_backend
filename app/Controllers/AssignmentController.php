<?php

namespace App\Controllers;

use App\Models\Assignment;
use App\Services\AuditService;
use App\Services\AssignmentService;

class AssignmentController
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
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = max(1, (int) ($_GET['limit'] ?? 10));

        $result = Assignment::getPaginated(
            $page,
            $limit
        );

        $this->json([
            'success' => true,
            'data' => $result['data'],
            'pagination' => $result['pagination']
        ]);
    }

    public function show(int $id): void
    {
        $assignment = Assignment::findById($id);

        if (!$assignment) {
            $this->json([
                'success' => false,
                'message' => 'Assignment not found'
            ], 404);
        }

        $this->json([
            'success' => true,
            'data' => $assignment
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

        try {
            $assignment = AssignmentService::assignAsset(
                $input['asset_id'],
                $input['assigned_to'],
                $input['assigned_by'],
                $input['expected_return_date'] ?? null,
                $input['notes'] ?? null
            );

            $this->json([
                'success' => true,
                'message' => 'Asset assigned successfully',
                'data' => $assignment
            ], 201);

        } catch (\Throwable $e) {
            $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function returnAsset(int $id): void
    {
        $input = json_decode(
            file_get_contents('php://input'),
            true
        ) ?? [];

        $old = Assignment::findById($id);

        if (!$old) {
            $this->json([
                'success' => false,
                'message' => 'Assignment not found'
            ], 404);
        }

        if ($old['status'] !== 'active') {
            $this->json([
                'success' => false,
                'message' => 'Assignment already closed'
            ], 422);
        }

        try {
            $updated = \App\Services\AssignmentService::returnAsset(
                $id,
                $input['return_notes'] ?? null,
                $input['condition_status'] ?? null,
                $input['processed_by'] ?? null
            );

            AuditService::logUpdate(
                'Assignment',
                $id,
                $old,
                $updated
            );

            $this->json([
                'success' => true,
                'message' => 'Asset returned successfully',
                'data' => $updated
            ]);

        } catch (\Throwable $e) {
            $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function assetHistory(int $assetId): void
    {
        $this->json([
            'success' => true,
            'data' => Assignment::findByAsset($assetId)
        ]);
    }

    public function activeByAsset(int $assetId): void
    {
        $assignment = Assignment::findActiveByAsset(
            $assetId
        );

        if (!$assignment) {
            $this->json([
                'success' => false,
                'message' => 'No active assignment found'
            ], 404);
        }

        $this->json([
            'success' => true,
            'data' => $assignment
        ]);
    }

    public function userAssignments(int $userId): void
    {
        $this->json([
            'success' => true,
            'data' => Assignment::findByUser($userId)
        ]);
    }
}