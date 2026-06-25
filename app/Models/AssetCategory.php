<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class AssetCategory
{
    public static function getAll(): array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->query(
            "SELECT * FROM asset_categories ORDER BY id DESC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById(int $id): ?array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "SELECT * FROM asset_categories WHERE id = :id LIMIT 1"
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
            "INSERT INTO asset_categories (name, description, depreciation_period)
             VALUES (:name, :description, :depreciation_period)"
        );

        $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'depreciation_period' => $data['depreciation_period'] ?? null
        ]);

        $id = (int)$db->lastInsertId();

        return self::findById($id);
    }

    public static function update(int $id, array $data): ?array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "UPDATE asset_categories
             SET name = :name,
                 description = :description,
                 depreciation_period = :depreciation_period
             WHERE id = :id"
        );

        $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'depreciation_period' => $data['depreciation_period'] ?? null
        ]);

        return self::findById($id);
    }

    public static function delete(int $id): bool
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "DELETE FROM asset_categories WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $id
        ]);
    }
}