<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class RevokedToken
{
    public static function revoke(
        string $jti,
        int $expiresAt
    ): bool
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            INSERT INTO revoked_tokens (
                jti,
                expires_at
            )
            VALUES (
                :jti,
                FROM_UNIXTIME(:expires_at)
            )
        ");

        return $stmt->execute([
            'jti' => $jti,
            'expires_at' => $expiresAt
        ]);
    }

    public static function isRevoked(
        string $jti
    ): bool
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            SELECT id
            FROM revoked_tokens
            WHERE jti = :jti
            LIMIT 1
        ");

        $stmt->execute([
            'jti' => $jti
        ]);

        return (bool)$stmt->fetch(PDO::FETCH_ASSOC);
    }
}