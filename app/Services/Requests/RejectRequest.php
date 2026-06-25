<?php

namespace App\Services\Requests;

use App\Models\Request;
use Exception;

class RejectRequest
{
    public static function execute(
        int $requestId,
        int $processedBy
    ): bool {

        $request = Request::findById($requestId);

        if (!$request) {
            throw new Exception('Request not found');
        }

        if ($request['status'] !== 'queued') {
            throw new Exception('Only queued requests can be rejected');
        }

        RequestStatusService::process(
            $requestId,
            'rejected',
            $processedBy
        );

        Request::moveQueueUp(
            (int)$request['asset_id'],
            (int)$request['queue_position']
        );

        return true;
    }
}