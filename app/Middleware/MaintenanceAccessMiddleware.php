<?php

namespace App\Middleware;

use App\Core\Router;
use App\Helpers\Auth;
use App\Models\AssetMaintenanceJob;

class MaintenanceAccessMiddleware
{
    public static function handle(): void
    {
        $user = Auth::user();

        if (!$user) {
            self::deny(401, 'Unauthenticated');
        }

        // Admins can access all maintenance jobs.
        if ($user['role'] === 'admin') {
            return;
        }

        $jobId = Router::instance()->getRouteParam(0);

        if ($jobId === null) {
            self::deny(400, 'Maintenance job ID is required');
        }

        $job = AssetMaintenanceJob::findById((int) $jobId);

        if (!$job) {
            self::deny(404, 'Maintenance job not found');
        }

        if ((int) $job['assigned_to'] !== (int) $user['id']) {
            self::deny(403, 'Forbidden');
        }
    }

    private static function deny(int $status, string $message): void
    {
        http_response_code($status);

        header('Content-Type: application/json');

        echo json_encode([
            'success' => false,
            'message' => $message
        ]);

        exit;
    }
}