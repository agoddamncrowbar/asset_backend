<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Assignment
{
    public static function getAll(): array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->query(
            "SELECT *
             FROM asset_assignments
             ORDER BY id DESC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getPaginated(
        int $page,
        int $limit
    ): array
    {
        $db = Database::getInstance()->getConnection();

        $offset = ($page - 1) * $limit;

        // Total records
        $countStmt = $db->query(
            "SELECT COUNT(*) as total
            FROM asset_assignments"
        );

        $total = (int) $countStmt->fetch(
            PDO::FETCH_ASSOC
        )['total'];

        // Page data
        $stmt = $db->prepare(
            "SELECT *
            FROM asset_assignments
            ORDER BY id DESC
            LIMIT :limit OFFSET :offset"
        );

        $stmt->bindValue(
            ':limit',
            $limit,
            PDO::PARAM_INT
        );

        $stmt->bindValue(
            ':offset',
            $offset,
            PDO::PARAM_INT
        );

        $stmt->execute();

        $data = $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );

        return [
            'data' => $data,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'total_pages' => (int) ceil(
                    $total / $limit
                )
            ]
        ];
    }
    public static function findById(int $id): ?array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "SELECT *
             FROM asset_assignments
             WHERE id = :id
             LIMIT 1"
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
            "INSERT INTO asset_assignments (
                asset_id,
                assigned_to,
                assigned_by,
                expected_return_date,
                notes
            )
            VALUES (
                :asset_id,
                :assigned_to,
                :assigned_by,
                :expected_return_date,
                :notes
            )"
        );

        $stmt->execute([
            'asset_id' => $data['asset_id'],
            'assigned_to' => $data['assigned_to'],
            'assigned_by' => $data['assigned_by'],
            'expected_return_date' => $data['expected_return_date'] ?? null,
            'notes' => $data['notes'] ?? null
        ]);

        $id = (int)$db->lastInsertId();

        return self::findById($id);
    }

    public static function markReturned(
        int $id,
        ?string $returnNotes = null
    ): ?array {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "UPDATE asset_assignments
             SET status = 'returned',
                 returned_at = NOW(),
                 return_notes = :return_notes
             WHERE id = :id"
        );

        $stmt->execute([
            'id' => $id,
            'return_notes' => $returnNotes
        ]);

        return self::findById($id);
    }

    public static function findActiveByAsset(
        int $assetId
    ): ?array {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "SELECT *
             FROM asset_assignments
             WHERE asset_id = :asset_id
               AND status = 'active'
             LIMIT 1"
        );

        $stmt->execute([
            'asset_id' => $assetId
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    public static function findByAsset(
        int $assetId
    ): array {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "SELECT *
             FROM asset_assignments
             WHERE asset_id = :asset_id
             ORDER BY assigned_at DESC"
        );

        $stmt->execute([
            'asset_id' => $assetId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findByUser(
        int $userId
    ): array {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "SELECT *
             FROM asset_assignments
             WHERE assigned_to = :user_id
             ORDER BY assigned_at DESC"
        );

        $stmt->execute([
            'user_id' => $userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}