<?php

namespace App\Services\AssetMaintenance;

use App\Models\AssetMaintenanceJob;
use App\Models\Asset;
use App\Models\AssetInspectionAsset;

class CompleteJob
{
    public static function handle(int $jobId, array $data): array
    {
        $job = AssetMaintenanceJob::findById($jobId);

        if (!$job) {
            throw new \Exception("Maintenance job not found");
        }

        if ($job['status'] !== 'in_progress') {
            throw new \Exception("Job must be in progress to complete");
        }

        $resolution = $data['resolution']; 
        // expected: fixed | not_repairable

        $notes = $data['resolution_notes'] ?? null;

        // 1. Update job
        $updatedJob = AssetMaintenanceJob::update($jobId, [
            'status'           => 'completed',
            'priority'         => $job['priority'],
            'assigned_to'      => $job['assigned_to'],
            'started_at'       => $job['started_at'],
            'completed_at'     => date('Y-m-d H:i:s'),
            'resolution_notes' => $notes
        ]);

        // 2. Update asset state
        if ($resolution === 'fixed') {
            Asset::updateStatus($job['asset_id'], 'available');
            Asset::updateCondition($job['asset_id'], 'good');
        }

        if ($resolution === 'not_repairable') {
            Asset::updateStatus($job['asset_id'], 'retired');
        }

        // 3. If linked to inspection, optionally update inspection result context
        if (!empty($job['inspection_id'])) {
            AssetInspectionAsset::updateResult(
                $job['inspection_id'],
                $job['asset_id'],
                [
                    'result' => $resolution === 'fixed' ? 'ok' : 'retire'
                ]
            );
        }

        return $updatedJob;
    }
}