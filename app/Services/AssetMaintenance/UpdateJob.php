<?php

namespace App\Services\AssetMaintenance;

use App\Models\AssetMaintenanceJob;

class UpdateJob
{
    public static function handle(int $jobId, array $data): array
    {
        $job = AssetMaintenanceJob::findById($jobId);

        if (!$job) {
            throw new \Exception("Job not found");
        }

        return AssetMaintenanceJob::update($jobId, [
            'status'           => $data['status'] ?? $job['status'],
            'priority'         => $data['priority'] ?? $job['priority'],
            'assigned_to'      => $data['assigned_to'] ?? $job['assigned_to'],
            'started_at'       => $job['started_at'],
            'completed_at'     => $job['completed_at'],
            'resolution_notes' => $job['resolution_notes']
        ]);
    }
}