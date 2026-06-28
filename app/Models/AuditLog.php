<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class AuditLog
{
    public static function create(array $data): bool
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            INSERT INTO audit_logs (
                user_id,
                action,
                entity_type,
                entity_id,
                old_values,
                new_values,
                metadata,
                ip_address,
                user_agent
            )
            VALUES (
                :user_id,
                :action,
                :entity_type,
                :entity_id,
                :old_values,
                :new_values,
                :metadata,
                :ip_address,
                :user_agent
            )
        ");

        return $stmt->execute([
            ':user_id' => $data['user_id'],
            ':action' => $data['action'],
            ':entity_type' => $data['entity_type'],
            ':entity_id' => $data['entity_id'],
            ':old_values' => $data['old_values']
                ? json_encode($data['old_values'])
                : null,
            ':new_values' => $data['new_values']
                ? json_encode($data['new_values'])
                : null,
            ':metadata' => $data['metadata']
                ? json_encode($data['metadata'])
                : null,
            ':ip_address' => $data['ip_address'],
            ':user_agent' => $data['user_agent']
        ]);
    }

    public static function findById(int $id): ?array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            SELECT *
            FROM audit_logs
            WHERE id = :id
        ");

        $stmt->execute([
            ':id' => $id
        ]);

        $log = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$log) {
            return null;
        }

        return self::decodeJsonFields($log);
    }

    public static function getAll(
        int $limit = 100,
        int $offset = 0
    ): array {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            SELECT *
            FROM audit_logs
            ORDER BY created_at DESC
            LIMIT :limit
            OFFSET :offset
        ");

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

        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            [self::class, 'decodeJsonFields'],
            $logs
        );
    }



    public static function search(string $query, int $limit = 50, int $offset = 0): array
    {
        $db = Database::getInstance()->getConnection();
        $search = '%' . trim($query) . '%';
        $limit = (int) $limit;
        $offset = (int) $offset;
        $sql = "
            SELECT *
            FROM audit_logs
            WHERE action LIKE ?
            OR entity_type LIKE ?
            OR entity_id LIKE ?
            OR CAST(user_id AS CHAR) LIKE ?
            ORDER BY created_at DESC
            LIMIT $limit OFFSET $offset
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $search,
            $search,
            $search,
            $search
        ]);
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(
            [self::class, 'decodeJsonFields'],
            $logs
        );
    }

    public static function searchCount(string $query): int
    {
        $db = Database::getInstance()->getConnection();
        $search = '%' . trim($query) . '%';
        $stmt = $db->prepare("
            SELECT COUNT(*)
            FROM audit_logs
            WHERE action LIKE ?
                OR entity_type LIKE ?
                OR entity_id LIKE ?
                OR CAST(user_id AS CHAR) LIKE ?
        ");
        $stmt->execute([
            $search,
            $search,
            $search,
            $search
        ]);
        return (int) $stmt->fetchColumn();
    }

    public static function count(): int
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->query("
            SELECT COUNT(*)
            FROM audit_logs
        ");

        return (int) $stmt->fetchColumn();
    }



    private static function decodeJsonFields(
        array $log
    ): array {
        $log['old_values'] = $log['old_values']
            ? json_decode($log['old_values'], true)
            : null;

        $log['new_values'] = $log['new_values']
            ? json_decode($log['new_values'], true)
            : null;

        $log['metadata'] = $log['metadata']
            ? json_decode($log['metadata'], true)
            : null;

        return $log;
    }



}