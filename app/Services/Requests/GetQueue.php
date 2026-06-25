<?php

namespace App\Services\Requests;

use App\Models\Request;

class GetQueue
{
    public static function execute(int $assetId): array
    {
        return Request::findQueuedByAsset($assetId);
    }
}