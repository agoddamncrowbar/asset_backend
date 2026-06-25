<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class AssetInspection
{
    public static function getAll(): array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->query(
            "SELECT * FROM asset_inspections ORDER BY id DESC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById(int $id): ?array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "SELECT * FROM asset_inspections WHERE id = :id LIMIT 1"
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
            "INSERT INTO asset_inspections
             (inspection_code, scheduled_date, status, notes, created_by)
             VALUES (:inspection_code, :scheduled_date, :status, :notes, :created_by)"
        );

        $stmt->execute([
            'inspection_code' => $data['inspection_code'],
            'scheduled_date'  => $data['scheduled_date'],
            'status'          => $data['status'] ?? 'scheduled',
            'notes'           => $data['notes'] ?? null,
            'created_by'      => $data['created_by']
        ]);

        $id = (int)$db->lastInsertId();

        return self::findById($id);
    }

    public static function update(int $id, array $data): ?array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "UPDATE asset_inspections
             SET scheduled_date = :scheduled_date,
                 status = :status,
                 notes = :notes,
                 completed_date = :completed_date,
                 completed_by = :completed_by
             WHERE id = :id"
        );

        $stmt->execute([
            'id'              => $id,
            'scheduled_date'  => $data['scheduled_date'],
            'status'          => $data['status'],
            'notes'           => $data['notes'] ?? null,
            'completed_date'  => $data['completed_date'] ?? null,
            'completed_by'    => $data['completed_by'] ?? null
        ]);

        return self::findById($id);
    }

    public static function delete(int $id): bool
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "DELETE FROM asset_inspections WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $id
        ]);
    }

    public static function updateStatus(int $id, string $status): ?array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "UPDATE assets
            SET asset_status = :status
            WHERE id = :id"
        );

        $stmt->execute([
            'id' => $id,
            'status' => $status
        ]);

        return self::findById($id);
    }

    public static function updateCondition(int $id, string $condition): ?array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "UPDATE assets
            SET condition_status = :condition
            WHERE id = :id"
        );

        $stmt->execute([
            'id' => $id,
            'condition' => $condition
        ]);

        return self::findById($id);
    }
}