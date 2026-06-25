<?php

namespace App\Services\Assignments;

use App\Models\Asset;
use App\Models\Assignment;
use App\Services\RequestService;
use Exception;

class ReturnAsset
{
    public static function execute(
        int $assignmentId,
        ?string $returnNotes = null,
        ?string $condition = null,
        ?int $processedBy = null
    ): array {

        $assignment = Assignment::findById($assignmentId);

        if (!$assignment) {
            throw new Exception('Assignment not found');
        }

        if ($assignment['status'] !== 'active') {
            throw new Exception('Assignment is already closed');
        }

        $updated = Assignment::markReturned(
            $assignmentId,
            $returnNotes
        );

        if (!$updated) {
            throw new Exception('Failed to close assignment');
        }

        if ($condition !== null) {
            Asset::updateCondition(
                (int)$assignment['asset_id'],
                $condition
            );
        }

        Asset::updateStatus(
            (int)$assignment['asset_id'],
            'available'
        );

        if ($processedBy !== null) {
            RequestService::autoPromoteNext(
                (int)$assignment['asset_id'],
                $processedBy
            );
        }

        return $updated;
    }
}