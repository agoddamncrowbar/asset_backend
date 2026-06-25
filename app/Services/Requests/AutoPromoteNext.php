<?php

namespace App\Services\Requests;

use App\Models\Request;
use App\Services\AssignmentService;

class AutoPromoteNext
{
    public static function execute(
        int $assetId,
        int $assignedBy
    ): ?array {

        $next = Request::findNextApprovedByAsset($assetId);

        if (!$next) {
            return null;
        }

        return AssignmentService::assignFromRequest(
            (int)$next['id'],
            $assignedBy
        );
    }
}