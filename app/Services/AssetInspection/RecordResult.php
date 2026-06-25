<?php

namespace App\Services\AssetInspection;

use App\Models\AssetInspectionAsset;

class RecordResult
{
    public static function handle(int $inspectionId, int $assetId, array $data): array
    {
        $existing = AssetInspectionAsset::find($inspectionId, $assetId);

        if (!$existing) {
            throw new \Exception("Asset not part of inspection");
        }

        return AssetInspectionAsset::updateResult(
            $inspectionId,
            $assetId,
            $data
        );
    }
}