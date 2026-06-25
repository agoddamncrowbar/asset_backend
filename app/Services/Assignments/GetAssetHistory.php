<?php

namespace App\Services\Assignments;

use App\Models\Assignment;

class GetAssetHistory
{
    public static function execute(
        int $assetId
    ): array {

        return Assignment::findByAsset(
            $assetId
        );
    }
}