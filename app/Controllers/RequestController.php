<?php

namespace App\Controllers;

use App\Services\RequestService;

class RequestController
{
    private function json(array $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json');

        echo json_encode($data);
        exit;
    }

    /**
     * Create a request
     */
    public function store(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);

        try {
            $request = RequestService::createRequest(
                (int)$input['asset_id'],
                (int)$input['requested_by'],
                $input['reason'] ?? null
            );

            $this->json([
                'success' => true,
                'message' => 'Request created successfully',
                'data' => $request
            ], 201);

        } catch (\Throwable $e) {
            $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Cancel a request
     */
    public function cancel(int $id): void
    {
        try {
            RequestService::cancelRequest($id);

            $this->json([
                'success' => true,
                'message' => 'Request cancelled successfully'
            ]);

        } catch (\Throwable $e) {
            $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Get queue for an asset
     */
    public function queue(int $assetId): void
    {
        $this->json([
            'success' => true,
            'data' => RequestService::getQueue($assetId)
        ]);
    }

    /**
     * Get next request in queue
     */
    public function next(int $assetId): void
    {
        $this->json([
            'success' => true,
            'data' => RequestService::getNextQueuedRequest($assetId)
        ]);
    }

    /**
     * Approve request (admin action)
     */
    public function approve(int $id): void
    {
        $input = json_decode(file_get_contents('php://input'), true);

        try {
            $processedBy = (int)($input['processed_by'] ?? 0);

            $request = RequestService::approveRequest(
                $id,
                $processedBy
            );

            $this->json([
                'success' => true,
                'message' => 'Request approved successfully',
                'data' => $request
            ]);

        } catch (\Throwable $e) {
            $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Reject request (admin action)
     */
    public function reject(int $id): void
    {
        $input = json_decode(file_get_contents('php://input'), true);

        try {
            $processedBy = (int)($input['processed_by'] ?? 0);

            RequestService::rejectRequest(
                $id,
                $processedBy
            );

            $this->json([
                'success' => true,
                'message' => 'Request rejected successfully'
            ]);

        } catch (\Throwable $e) {
            $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}