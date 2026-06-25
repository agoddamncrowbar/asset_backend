<?php

namespace App\Controllers;

use App\Models\AuditLog;
use App\Services\AuditFormatter;
use App\Services\AuditSummaryFormatter;

class AuditController
{
    public function index(): void
    {
        $logs = AuditLog::getAll();

        $formattedLogs = array_map(
            [AuditFormatter::class, 'format'],
            $logs
        );

        echo json_encode([
            'success' => true,
            'data' => $formattedLogs
        ]);
    }

    public function show(
        int $id
    ): void {
        $log = AuditLog::findById($id);

        if (!$log) {

            http_response_code(404);

            echo json_encode([
                'success' => false,
                'message' => 'Audit log not found'
            ]);

            return;
        }

        echo json_encode([
            'success' => true,
            'data' => AuditFormatter::format($log)
        ]);
    }
    public function summaries(): void
    {
        $logs = AuditLog::getAll();

        $formattedLogs = array_map(
            [AuditSummaryFormatter::class, 'format'],
            $logs
        );

        echo json_encode([
            'success' => true,
            'data' => $formattedLogs
        ]);
    }

    public function summary(
        int $id
    ): void {
        $log = AuditLog::findById($id);

        if (!$log) {

            http_response_code(404);

            echo json_encode([
                'success' => false,
                'message' => 'Audit log not found'
            ]);

            return;
        }

        echo json_encode([
            'success' => true,
            'data' => AuditSummaryFormatter::format($log)
        ]);
    }
    
}