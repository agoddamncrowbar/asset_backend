<?php

namespace App\Services\Requests;

use App\Models\Request;
use Exception;

class RequestStatusService
{
    public static function process(
        int $id,
        string $status,
        int $processedBy
    ): bool {

        $request = Request::findById($id);

        if (!$request) {
            throw new Exception('Request not found');
        }

        $allowed = [
            'queued' => ['approved', 'rejected', 'cancelled'],
        ];

        if (
            !isset($allowed[$request['status']]) ||
            !in_array($status, $allowed[$request['status']], true)
        ) {
            throw new Exception("Invalid transition: {$request['status']} → $status");
        }

        $db = \App\Core\Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            UPDATE asset_requests
            SET status = :status,
                processed_by = :processed_by,
                processed_at = NOW()
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'status' => $status,
            'processed_by' => $processedBy
        ]);
    }
}