<?php

namespace App\Services\Requests;

use App\Models\Request;

class GetNextQueuedRequest
{
    public static function execute(int $assetId): ?array
    {
        return Request::findNextQueuedRequest($assetId);
    }
}