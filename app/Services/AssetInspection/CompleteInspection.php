<?php

namespace App\Services\AssetInspection;

use App\Models\AssetInspection;
use App\Models\AssetInspectionAsset;
use App\Models\Asset;
use App\Models\AssetMaintenanceJob;

class CompleteInspection
{
    public static function handle(int $inspectionId, int $userId): array
    {
        $inspection = AssetInspection::findById($inspectionId);

        if (!$inspection) {
            throw new \Exception("Inspection not found");
        }

        $assets = AssetInspectionAsset::getByInspectionId($inspectionId);

        foreach ($assets as $asset) {

            switch ($asset['result']) {

                case 'needs_repair':
                    AssetMaintenanceJob::create([
                        'asset_id'      => $asset['asset_id'],
                        'inspection_id' => $inspectionId,
                        'issue_report'  => $asset['remarks'] ?? 'Inspection flagged issue',
                        'created_by'    => $userId
                    ]);

                    Asset::updateStatus($asset['asset_id'], 'maintenance');
                    break;

                case 'damaged':
                    Asset::updateCondition($asset['asset_id'], 'damaged');
                    Asset::updateStatus($asset['asset_id'], 'maintenance');
                    break;

                case 'retire':
                    Asset::updateStatus($asset['asset_id'], 'retired');
                    break;
            }
        }

        return AssetInspection::update($inspectionId, [
            'scheduled_date' => $inspection['scheduled_date'],
            'status' => 'completed',
            'notes' => $inspection['notes'],
            'completed_date' => date('Y-m-d H:i:s'),
            'completed_by' => $userId
        ]);
    }
}