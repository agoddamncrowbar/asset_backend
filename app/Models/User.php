<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class User
{
    private static function db(): PDO
    {
        return Database::getInstance()->getConnection();
    }

    public static function getAll(): array
    {
        $stmt = self::db()->prepare("
            SELECT id, university_id, first_name, last_name, email, phone,
                   role, status, must_change_password, created_at, updated_at
            FROM users
            ORDER BY id DESC
        ");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById(int $id): ?array
    {
        $stmt = self::db()->prepare("
            SELECT *
            FROM users
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public static function findByEmail(string $email): ?array
    {
        $stmt = self::db()->prepare("
            SELECT *
            FROM users
            WHERE email = ?
            LIMIT 1
        ");

        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public static function create(array $data): int|false
    {
        $stmt = self::db()->prepare("
            INSERT INTO users (
                university_id,
                first_name,
                last_name,
                email,
                phone,
                password_hash,
                role,
                status,
                must_change_password
            ) VALUES (
                :university_id,
                :first_name,
                :last_name,
                :email,
                :phone,
                :password_hash,
                :role,
                :status,
                :must_change_password
            )
        ");

        $stmt->execute([
            'university_id' => $data['university_id'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password_hash' => $data['password_hash'],
            'role' => $data['role'],
            'status' => $data['status'] ?? 'active',
            'must_change_password' => $data['must_change_password'] ?? 1
        ]);

        return (int) self::db()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = self::db()->prepare("
            UPDATE users
            SET
                university_id = :university_id,
                first_name = :first_name,
                last_name = :last_name,
                email = :email,
                phone = :phone,
                role = :role,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'university_id' => $data['university_id'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'role' => $data['role']
        ]);
    }

    public static function suspend(int $id): bool
    {
        return self::setStatus($id, 'suspended');
    }

    public static function activate(int $id): bool
    {
        return self::setStatus($id, 'active');
    }

    public static function delete(int $id): bool
    {
        $stmt = self::db()->prepare("
            DELETE FROM users
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }

    private static function setStatus(int $id, string $status): bool
    {
        $stmt = self::db()->prepare("
            UPDATE users
            SET status = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");

        return $stmt->execute([$status, $id]);
    }

    public static function updatePassword(int $id, string $passwordHash): bool
    {
        $stmt = self::db()->prepare("
            UPDATE users
            SET password_hash = ?, must_change_password = 0, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");

        return $stmt->execute([$passwordHash, $id]);
    }
}