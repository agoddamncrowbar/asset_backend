<?php

namespace App\Services\Requests;

use App\Models\Request;
use Exception;

class CancelRequest
{
    public static function execute(int $requestId): bool
    {
        $request = Request::findById($requestId);

        if (!$request) {
            throw new Exception('Request not found');
        }

        if ($request['status'] !== 'queued') {
            throw new Exception('Only queued requests can be cancelled');
        }

        Request::updateStatus($requestId, 'cancelled');

        Request::moveQueueUp(
            (int)$request['asset_id'],
            (int)$request['queue_position']
        );

        return true;
    }
}