<?php

namespace App\Services\Assignments;

use App\Models\Assignment;

class GetActiveAssignment
{
    public static function execute(
        int $assetId
    ): ?array {

        return Assignment::findActiveByAsset(
            $assetId
        );
    }
}