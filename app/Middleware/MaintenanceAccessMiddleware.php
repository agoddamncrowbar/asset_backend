<?php

namespace App\Middleware;

use App\Helpers\Auth;
use App\Models\AssetMaintenance;

class MaintenanceAccessMiddleware
{
    public static function handle(): void
    {
        $user = Auth::user();

        if (!$user) {
            self::deny(401, "Unauthenticated");
        }

        // Admins can access everything
        if ($user['role'] === 'admin') {
            return;
        }

        // Get maintenance ID from route parameters
        $maintenanceId = $_REQUEST['id'] ?? null;

        if (!$maintenanceId) {
            self::deny(400, "ID missing");
        }

        $maintenance = AssetMaintenance::findById($maintenanceId);

        if (!$maintenance) {
            self::deny(404, "Maintenance job not found");
        }

        if ($maintenance['assigned_to'] != $user['id']) {
            self::deny(403, "Forbidden");
        }
    }

    private static function deny(int $status, string $message): void
    {
        http_response_code($status);

        echo json_encode([
            "success" => false,
            "message" => $message
        ]);

        exit;
    }
}