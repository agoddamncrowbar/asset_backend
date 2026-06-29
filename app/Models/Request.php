<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Request
{
    public static function create(array $data): array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            INSERT INTO asset_requests (
                asset_id,
                requested_by,
                reason,
                queue_position
            )
            VALUES (
                :asset_id,
                :requested_by,
                :reason,
                :queue_position
            )
        ");

        $stmt->execute([
            'asset_id' => $data['asset_id'],
            'requested_by' => $data['requested_by'],
            'reason' => $data['reason'] ?? null,
            'queue_position' => $data['queue_position']
        ]);

        return self::findById((int)$db->lastInsertId());
    }

    public static function findById(int $id): ?array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            SELECT *
            FROM asset_requests
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute(['id' => $id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    public static function findQueuedByAsset(int $assetId): array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            SELECT *
            FROM asset_requests
            WHERE asset_id = :asset_id
            AND status IN ('queued', 'approved')
            ORDER BY queue_position ASC
        ");

        $stmt->execute(['asset_id' => $assetId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findActiveRequest(int $assetId, int $userId): ?array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            SELECT *
            FROM asset_requests
            WHERE asset_id = :asset_id
              AND requested_by = :requested_by
              AND status = 'queued'
            LIMIT 1
        ");

        $stmt->execute([
            'asset_id' => $assetId,
            'requested_by' => $userId
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    public static function getNextQueuePosition(int $assetId): int
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            SELECT COALESCE(MAX(queue_position), 0) + 1
            FROM asset_requests
            WHERE asset_id = :asset_id
              AND status IN ('queued', 'approved')
        ");

        $stmt->execute(['asset_id' => $assetId]);

        return (int)$stmt->fetchColumn();
    }

    public static function moveQueueUp(int $assetId, int $fromPosition): void
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            UPDATE asset_requests
            SET queue_position = queue_position - 1
            WHERE asset_id = :asset_id
              AND status = 'queued'
              AND queue_position > :position
        ");

        $stmt->execute([
            'asset_id' => $assetId,
            'position' => $fromPosition
        ]);
    }

    public static function updateStatus(int $id, string $status): bool
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            UPDATE asset_requests
            SET status = :status
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'status' => $status
        ]);
    }

    public static function findNextQueuedRequest(int $assetId): ?array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            SELECT *
            FROM asset_requests
            WHERE asset_id = :asset_id
              AND status = 'queued'
            ORDER BY queue_position ASC
            LIMIT 1
        ");

        $stmt->execute(['asset_id' => $assetId]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    public static function findNextApprovedByAsset(int $assetId): ?array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            SELECT *
            FROM asset_requests
            WHERE asset_id = :asset_id
            AND status = 'approved'
            ORDER BY id ASC
            LIMIT 1
        ");

        $stmt->execute(['asset_id' => $assetId]);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    public static function fulfill(int $id, int $processedBy): bool
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            UPDATE asset_requests
            SET
                status = 'fulfilled',
                processed_by = :processed_by,
                processed_at = NOW()
            WHERE id = :id
              AND status = 'approved'
        ");

        return $stmt->execute([
            'id' => $id,
            'processed_by' => $processedBy
        ]);
    }
}