<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Asset
{
    public static function create(array $data): int
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            INSERT INTO assets (
                asset_tag,
                serial_number,
                item_name,
                description,
                category_id,
                department_id,
                location_id,
                purchase_date,
                purchase_cost,
                supplier,
                condition_status,
                asset_status,
                created_by
            )
            VALUES (
                :asset_tag,
                :serial_number,
                :item_name,
                :description,
                :category_id,
                :department_id,
                :location_id,
                :purchase_date,
                :purchase_cost,
                :supplier,
                :condition_status,
                :asset_status,
                :created_by
            )
        ");

        $stmt->execute([
            'asset_tag' => $data['asset_tag'],
            'serial_number' => $data['serial_number'] ?? null,
            'item_name' => $data['item_name'],
            'description' => $data['description'] ?? null,
            'category_id' => $data['category_id'],
            'department_id' => $data['department_id'],
            'location_id' => $data['location_id'],
            'purchase_date' => $data['purchase_date'] ?? null,
            'purchase_cost' => $data['purchase_cost'] ?? null,
            'supplier' => $data['supplier'] ?? null,
            'condition_status' => $data['condition_status'] ?? 'good',
            'asset_status' => $data['asset_status'] ?? 'available',
            'created_by' => $data['created_by']
        ]);

        return (int) $db->lastInsertId();
    }

    public static function findById(int $id): ?array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            SELECT *
            FROM assets
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $id
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    public static function findByTag(string $assetTag): ?array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            SELECT *
            FROM assets
            WHERE asset_tag = :asset_tag
            LIMIT 1
        ");

        $stmt->execute([
            'asset_tag' => $assetTag
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    public static function findAll(int $limit = 50, int $offset = 0): array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            SELECT *
            FROM assets
            ORDER BY id DESC
            LIMIT :limit OFFSET :offset
        ");

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function update(int $id, array $data): bool
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            UPDATE assets
            SET
                serial_number = :serial_number,
                item_name = :item_name,
                description = :description,
                category_id = :category_id,
                department_id = :department_id,
                location_id = :location_id,
                purchase_date = :purchase_date,
                purchase_cost = :purchase_cost,
                supplier = :supplier,
                condition_status = :condition_status,
                asset_status = :asset_status,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'serial_number' => $data['serial_number'] ?? null,
            'item_name' => $data['item_name'],
            'description' => $data['description'] ?? null,
            'category_id' => $data['category_id'],
            'department_id' => $data['department_id'],
            'location_id' => $data['location_id'],
            'purchase_date' => $data['purchase_date'] ?? null,
            'purchase_cost' => $data['purchase_cost'] ?? null,
            'supplier' => $data['supplier'] ?? null,
            'condition_status' => $data['condition_status'] ?? 'good',
            'asset_status' => $data['asset_status'] ?? 'available'
        ]);
    }

    public static function retire(int $id): bool
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            UPDATE assets
            SET asset_status = 'retired',
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id
        ]);
    }

    public static function updateStatus(int $id, string $status): bool
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            UPDATE assets
            SET asset_status = :status,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ");

        $stmt->execute([
            'id' => $id,
            'status' => $status
        ]);

        if ($stmt->rowCount() === 0) {
            throw new \RuntimeException(
                "Asset status not updated (no rows affected). id={$id}, status={$status}"
            );
        }

        return true;
    }

    public static function updateCondition(int $id, string $condition): bool
    {
        $allowed = ['excellent', 'good', 'fair', 'poor', 'damaged'];

        if (!in_array($condition, $allowed, true)) {
            throw new \InvalidArgumentException(
                "Invalid condition status: {$condition}"
            );
        }

        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            UPDATE assets
            SET condition_status = :condition,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ");

        $stmt->execute([
            'id' => $id,
            'condition' => $condition
        ]);

        if ($stmt->rowCount() === 0) {
            throw new \RuntimeException(
                "Condition not updated (no rows affected). id={$id}, condition={$condition}"
            );
        }

        return true;
    }
}