<?php

namespace App\Services\AssetMaintenance;

use App\Models\AssetMaintenanceJob;

class CreateJob
{
    public static function handle(array $data): array
    {
        if (empty($data['issue_report'])) {
            throw new \Exception("Issue report is required");
        }

        return AssetMaintenanceJob::create([
            'asset_id'      => $data['asset_id'],
            'inspection_id' => $data['inspection_id'] ?? null,
            'priority'      => $data['priority'] ?? 'medium',
            'issue_report'  => $data['issue_report'],
            'assigned_to'   => $data['assigned_to'] ?? null,
            'created_by'    => $data['created_by']
        ]);
    }
}