<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class SearchUser
{
    public static function findById(int $id): ?array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "SELECT *
             FROM users
             WHERE id = :id
             LIMIT 1"
        );

        $stmt->execute([
            'id' => $id
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    /**
     * Search by university ID, first name, last name,
     * or full name.
     */
    public static function search(string $query): array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "SELECT
                id,
                university_id,
                first_name,
                last_name,
                email,
                role,
                status
             FROM users
             WHERE university_id LIKE :search
                OR first_name LIKE :search
                OR last_name LIKE :search
                OR CONCAT(first_name, ' ', last_name) LIKE :search
             ORDER BY first_name ASC, last_name ASC
             LIMIT 20"
        );

        $stmt->execute([
            'search' => "%{$query}%"
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Check whether a user currently has any
     * active assignments.
     */
    public static function hasPendingReturns(int $userId): bool
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "SELECT COUNT(*) AS total
             FROM asset_assignments
             WHERE assigned_to = :user_id
               AND status = 'active'"
        );

        $stmt->execute([
            'user_id' => $userId
        ]);

        return (int)$stmt->fetchColumn() > 0;
    }

    /**
     * Return all active assignments that have not
     * yet been returned.
     */
    public static function getPendingReturns(int $userId): array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "SELECT *
             FROM asset_assignments
             WHERE assigned_to = :user_id
               AND status = 'active'
             ORDER BY assigned_at DESC"
        );

        $stmt->execute([
            'user_id' => $userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Check whether a user has any request still
     * in the workflow.
     */
    public static function hasPendingRequests(int $userId): bool
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "SELECT COUNT(*) AS total
             FROM asset_requests
             WHERE requested_by = :user_id
               AND status IN ('queued', 'approved')"
        );

        $stmt->execute([
            'user_id' => $userId
        ]);

        return (int)$stmt->fetchColumn() > 0;
    }

    /**
     * Get all requests that are still pending.
     */
    public static function getPendingRequests(int $userId): array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "SELECT *
             FROM asset_requests
             WHERE requested_by = :user_id
               AND status IN ('queued', 'approved')
             ORDER BY requested_at DESC"
        );

        $stmt->execute([
            'user_id' => $userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Single endpoint helper.
     */
    public static function getUserAssetStatus(int $userId): array
    {
        return [
            'has_pending_returns' => self::hasPendingReturns($userId),
            'has_pending_requests' => self::hasPendingRequests($userId),
            'pending_returns' => self::getPendingReturns($userId),
            'pending_requests' => self::getPendingRequests($userId),
        ];
    }
}