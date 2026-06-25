<?php

namespace App\Services\AssetInspection;

use App\Models\AssetInspectionAsset;

class AddAssets
{
    public static function handle(int $inspectionId, array $assetIds): void
    {
        foreach ($assetIds as $assetId) {
            AssetInspectionAsset::create([
                'inspection_id' => $inspectionId,
                'asset_id'      => $assetId
            ]);
        }
    }
}