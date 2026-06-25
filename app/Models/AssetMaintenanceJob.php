<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class AssetMaintenanceJob
{
    public static function getAll(): array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->query(
            "SELECT * FROM asset_maintenance_jobs ORDER BY id DESC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById(int $id): ?array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "SELECT * FROM asset_maintenance_jobs WHERE id = :id LIMIT 1"
        );

        $stmt->execute([
            'id' => $id
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    public static function create(array $data): array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "INSERT INTO asset_maintenance_jobs
             (asset_id, inspection_id, status, priority, issue_report, assigned_to, created_by)
             VALUES (:asset_id, :inspection_id, :status, :priority, :issue_report, :assigned_to, :created_by)"
        );

        $stmt->execute([
            'asset_id'      => $data['asset_id'],
            'inspection_id' => $data['inspection_id'] ?? null,
            'status'        => $data['status'] ?? 'queued',
            'priority'      => $data['priority'] ?? 'medium',
            'issue_report'  => $data['issue_report'],
            'assigned_to'   => $data['assigned_to'] ?? null,
            'created_by'    => $data['created_by']
        ]);

        $id = (int)$db->lastInsertId();

        return self::findById($id);
    }

    public static function update(int $id, array $data): ?array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "UPDATE asset_maintenance_jobs
             SET status = :status,
                 priority = :priority,
                 assigned_to = :assigned_to,
                 started_at = :started_at,
                 completed_at = :completed_at,
                 resolution_notes = :resolution_notes
             WHERE id = :id"
        );

        $stmt->execute([
            'id'               => $id,
            'status'           => $data['status'],
            'priority'         => $data['priority'] ?? 'medium',
            'assigned_to'      => $data['assigned_to'] ?? null,
            'started_at'       => $data['started_at'] ?? null,
            'completed_at'     => $data['completed_at'] ?? null,
            'resolution_notes' => $data['resolution_notes'] ?? null
        ]);

        return self::findById($id);
    }

    public static function delete(int $id): bool
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "DELETE FROM asset_maintenance_jobs WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $id
        ]);
    }
}