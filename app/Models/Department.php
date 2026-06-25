<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Department
{
    public static function getAll(): array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->query(
            "SELECT * FROM departments ORDER BY id DESC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById(int $id): ?array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "SELECT * FROM departments WHERE id = :id LIMIT 1"
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
            "INSERT INTO departments (name, code, description)
             VALUES (:name, :code, :description)"
        );

        $stmt->execute([
            'name' => $data['name'],
            'code' => $data['code'],
            'description' => $data['description'] ?? null
        ]);

        $id = (int)$db->lastInsertId();

        return self::findById($id);
    }

    public static function update(int $id, array $data): ?array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "UPDATE departments
             SET name = :name,
                 code = :code,
                 description = :description
             WHERE id = :id"
        );

        $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'code' => $data['code'],
            'description' => $data['description'] ?? null
        ]);

        return self::findById($id);
    }

    public static function delete(int $id): bool
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "DELETE FROM departments WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $id
        ]);
    }
}