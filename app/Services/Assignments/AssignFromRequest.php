<?php

namespace App\Services\Assignments;

use App\Models\Request;
use App\Models\Asset;
use App\Models\User;
use App\Models\Assignment;
use Exception;

class AssignFromRequest
{
    public static function execute(
        int $requestId,
        int $assignedBy
    ): array {

        $request = Request::findById($requestId);

        if (!$request) {
            throw new Exception('Request not found');
        }

        if ($request['status'] !== 'approved') {
            throw new Exception('Only approved requests can be fulfilled');
        }

        $asset = Asset::findById($request['asset_id']);

        if (!$asset) {
            throw new Exception('Asset not found');
        }

        if ($asset['asset_status'] !== 'available') {
            throw new Exception('Asset is not available');
        }

        $user = User::findById($request['requested_by']);

        if (!$user || $user['status'] !== 'active') {
            throw new Exception('Invalid request user');
        }

        $assignment = Assignment::create([
            'asset_id' => $request['asset_id'],
            'assigned_to' => $request['requested_by'],
            'assigned_by' => $assignedBy,
            'expected_return_date' => null,
            'notes' => 'Created from approved request'
        ]);

        if (!$assignment) {
            throw new Exception('Failed to create assignment');
        }

        $updated = Asset::updateStatus($request['asset_id'], 'assigned');

        if (!$updated) {

            Assignment::markReturned(
                $assignment['id'],
                'Rollback: asset update failed'
            );

            throw new Exception('Failed to update asset status');
        }

        Request::updateStatus($requestId, 'fulfilled');

        return $assignment;
    }
}