<?php

namespace App\Services\AssetInspection;

use App\Models\AssetInspection;

class StartInspection
{
    public static function handle(int $inspectionId): array
    {
        $inspection = AssetInspection::findById($inspectionId);

        if (!$inspection) {
            throw new \Exception("Inspection not found");
        }

        if ($inspection['status'] !== 'scheduled') {
            throw new \Exception("Inspection cannot be started");
        }

        return AssetInspection::update($inspectionId, [
            'scheduled_date' => $inspection['scheduled_date'],
            'status' => 'in_progress',
            'notes' => $inspection['notes'],
            'completed_date' => null,
            'completed_by' => null
        ]);
    }
}