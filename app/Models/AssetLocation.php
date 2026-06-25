<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class AssetLocation
{
    public static function getAll(): array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->query(
            "SELECT * FROM asset_locations ORDER BY id DESC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById(int $id): ?array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "SELECT * FROM asset_locations WHERE id = :id LIMIT 1"
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
            "INSERT INTO asset_locations (name, building, room_number, description)
             VALUES (:name, :building, :room_number, :description)"
        );

        $stmt->execute([
            'name' => $data['name'],
            'building' => $data['building'] ?? null,
            'room_number' => $data['room_number'] ?? null,
            'description' => $data['description'] ?? null
        ]);

        $id = (int)$db->lastInsertId();

        return self::findById($id);
    }

    public static function update(int $id, array $data): ?array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "UPDATE asset_locations
             SET name = :name,
                 building = :building,
                 room_number = :room_number,
                 description = :description
             WHERE id = :id"
        );

        $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'building' => $data['building'] ?? null,
            'room_number' => $data['room_number'] ?? null,
            'description' => $data['description'] ?? null
        ]);

        return self::findById($id);
    }

    public static function delete(int $id): bool
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "DELETE FROM asset_locations WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $id
        ]);
    }
}