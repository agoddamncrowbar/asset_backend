<?php

namespace App\Services\Assignments;

use App\Models\Asset;
use App\Models\User;
use App\Models\Assignment;
use Exception;

class AssignAsset
{
    public static function execute(
        int $assetId,
        int $assignedTo,
        int $assignedBy,
        ?string $expectedReturnDate = null,
        ?string $notes = null
    ): array {

        $asset = Asset::findById($assetId);

        if (!$asset) {
            throw new Exception('Asset not found');
        }

        if ($asset['asset_status'] !== 'available') {
            throw new Exception('Asset is not available for assignment');
        }

        $user = User::findById($assignedTo);

        if (!$user || $user['status'] !== 'active') {
            throw new Exception('Invalid assignee');
        }

        if (Assignment::findActiveByAsset($assetId)) {
            throw new Exception('Asset already assigned');
        }

        $assignment = Assignment::create([
            'asset_id' => $assetId,
            'assigned_to' => $assignedTo,
            'assigned_by' => $assignedBy,
            'expected_return_date' => $expectedReturnDate,
            'notes' => $notes
        ]);

        if (!$assignment) {
            throw new Exception('Failed to create assignment');
        }

        $updated = Asset::updateStatus($assetId, 'assigned');

        if (!$updated) {

            Assignment::markReturned(
                $assignment['id'],
                'Auto rollback: asset update failed'
            );

            throw new Exception('Failed to update asset status');
        }

        return $assignment;
    }
}