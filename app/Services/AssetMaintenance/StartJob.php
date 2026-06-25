<?php

namespace App\Services\AssetMaintenance;

use App\Models\AssetMaintenanceJob;

class StartJob
{
    public static function handle(int $jobId): array
    {
        $job = AssetMaintenanceJob::findById($jobId);

        if (!$job) {
            throw new \Exception("Maintenance job not found");
        }

        if ($job['status'] !== 'queued') {
            throw new \Exception("Job cannot be started");
        }

        return AssetMaintenanceJob::update($jobId, [
            'status'           => 'in_progress',
            'priority'         => $job['priority'],
            'assigned_to'      => $job['assigned_to'],
            'started_at'       => date('Y-m-d H:i:s'),
            'completed_at'     => null,
            'resolution_notes' => null
        ]);
    }
}