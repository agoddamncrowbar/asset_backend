<?php

namespace App\Services\Requests;

use App\Models\Asset;
use App\Models\Request;
use App\Services\AssignmentService;
use Exception;

class ApproveRequest
{
    public static function execute(
        int $requestId,
        int $processedBy
    ): array {

        $request = Request::findById($requestId);

        if (!$request) {
            throw new Exception('Request not found');
        }

        if ($request['status'] !== 'queued') {
            throw new Exception('Only queued requests can be approved');
        }

        RequestStatusService::process(
            $requestId,
            'approved',
            $processedBy
        );

        $request = Request::findById($requestId);

        $asset = Asset::findById((int)$request['asset_id']);

        if ($asset && $asset['asset_status'] === 'available') {

            AssignmentService::assignFromRequest(
                $requestId,
                $processedBy
            );

            return Request::findById($requestId);
        }

        return $request;
    }
}