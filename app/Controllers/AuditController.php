<?php

namespace App\Controllers;
use Exception;

use App\Models\AuditLog;
use App\Services\AuditFormatter;
use App\Services\AuditSummaryFormatter;

class AuditController
{
    private function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    public function index(): void
    {
        try {
            $limit = (int)($_GET['limit'] ?? 50);
            $offset = (int)($_GET['offset'] ?? 0);

            $result = $this->getAuditLogs($limit, $offset);

            $this->json([
                'success' => true,
                'data' => $result['data'],
                'pagination' => $result['pagination']
            ]);
        } catch (Exception $e) {
            $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
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
        try {
            $limit = (int)($_GET['limit'] ?? 50);
            $offset = (int)($_GET['offset'] ?? 0);

            $result = $this->getAuditSummaries($limit, $offset);

            $this->json([
                'success' => true,
                'data' => $result['data'],
                'pagination' => $result['pagination']
            ]);
        } catch (Exception $e) {
            $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function summaryById(
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
    public function getAuditLogs(
        int $limit,
        int $offset
    ): array {

        $logs = AuditLog::getAll($limit, $offset);
        $total = AuditLog::count();

        return [
            'data' => array_map(
                [AuditFormatter::class, 'format'],
                $logs
            ),
            'pagination' => [
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset
            ]
        ];
    }
    public function getAuditSummaries(
        int $limit,
        int $offset
    ): array {

        $logs = AuditLog::getAll($limit, $offset);
        $total = AuditLog::count();

        return [
            'data' => array_map(
                [AuditSummaryFormatter::class, 'format'],
                $logs
            ),
            'pagination' => [
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset
            ]
        ];
    }
    public function exportCsv(): void
    {
        $logs = AuditLog::getAll(PHP_INT_MAX, 0);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="audit_logs.csv"');

        $output = fopen('php://output', 'w');

        fputcsv($output, [
            'ID',
            'Timestamp',
            'User ID',
            'Action',
            'Entity Type',
            'Entity ID',
            'Message',
            'Changes'
        ]);

        foreach ($logs as $log) {

            $formatted = AuditFormatter::format($log);

            $changes = [];

            foreach ($formatted['changes'] as $change) {
                $changes[] = sprintf(
                    '%s: %s -> %s',
                    $change['field'],
                    $change['old'] ?? '',
                    $change['new'] ?? ''
                );
            }

            fputcsv($output, [
                $formatted['id'],
                $formatted['timestamp'],
                $formatted['user_id'],
                $formatted['action'],
                $formatted['entity_type'],
                $formatted['entity_id'],
                $formatted['message'],
                implode('; ', $changes)
            ]);
        }

        fclose($output);
        exit;
    }

    public function search(): void
    {
        try {
            $query = trim($_GET['q'] ?? '');
            $limit = (int)($_GET['limit'] ?? 50);
            $offset = (int)($_GET['offset'] ?? 0);

            if ($query === '') {
                $this->json([
                    'success' => false,
                    'message' => 'Search query is required.'
                ], 400);
                return;
            }

            $logs = AuditLog::search($query, $limit, $offset);
            $total = AuditLog::searchCount($query);

            $this->json([
                'success' => true,
                'data' => array_map(
                    [AuditSummaryFormatter::class, 'format'],
                    $logs
                ),
                'pagination' => [
                    'total' => $total,
                    'limit' => $limit,
                    'offset' => $offset
                ]
            ]);
        } catch (Exception $e) {
            $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}