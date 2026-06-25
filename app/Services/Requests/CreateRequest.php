<?php

namespace App\Services\Requests;

use App\Models\Asset;
use App\Models\Request;
use App\Models\User;
use Exception;

class CreateRequest
{
    public static function execute(
        int $assetId,
        int $userId,
        ?string $reason
    ): array {

        $asset = Asset::findById($assetId);

        if (!$asset) {
            throw new Exception('Asset not found');
        }

        if ($asset['asset_status'] === 'available') {
            throw new Exception('Asset is available; no request required');
        }

        $user = User::findById($userId);

        if (!$user || $user['status'] !== 'active') {
            throw new Exception('Invalid user');
        }

        $existing = Request::findActiveRequest($assetId, $userId);

        if ($existing) {
            throw new Exception('You already have a pending request for this asset');
        }

        $position = Request::getNextQueuePosition($assetId);

        return Request::create([
            'asset_id' => $assetId,
            'requested_by' => $userId,
            'reason' => $reason,
            'queue_position' => $position
        ]);
    }
}