<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class AssetInspectionAsset
{
    public static function getByInspectionId(int $inspectionId): array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "SELECT * FROM asset_inspection_assets
             WHERE inspection_id = :inspection_id"
        );

        $stmt->execute([
            'inspection_id' => $inspectionId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find(int $inspectionId, int $assetId): ?array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "SELECT * FROM asset_inspection_assets
             WHERE inspection_id = :inspection_id
             AND asset_id = :asset_id
             LIMIT 1"
        );

        $stmt->execute([
            'inspection_id' => $inspectionId,
            'asset_id'      => $assetId
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    public static function create(array $data): array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "INSERT INTO asset_inspection_assets
             (inspection_id, asset_id, result, condition_after, remarks)
             VALUES (:inspection_id, :asset_id, :result, :condition_after, :remarks)"
        );

        $stmt->execute([
            'inspection_id'   => $data['inspection_id'],
            'asset_id'        => $data['asset_id'],
            'result'          => $data['result'] ?? null,
            'condition_after' => $data['condition_after'] ?? null,
            'remarks'         => $data['remarks'] ?? null
        ]);

        return self::find($data['inspection_id'], $data['asset_id']);
    }

    public static function updateResult(int $inspectionId, int $assetId, array $data): ?array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "UPDATE asset_inspection_assets
             SET result = :result,
                 condition_after = :condition_after,
                 remarks = :remarks
             WHERE inspection_id = :inspection_id
             AND asset_id = :asset_id"
        );

        $stmt->execute([
            'inspection_id'   => $inspectionId,
            'asset_id'        => $assetId,
            'result'          => $data['result'],
            'condition_after' => $data['condition_after'] ?? null,
            'remarks'         => $data['remarks'] ?? null
        ]);

        return self::find($inspectionId, $assetId);
    }

    public static function delete(int $inspectionId, int $assetId): bool
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "DELETE FROM asset_inspection_assets
             WHERE inspection_id = :inspection_id
             AND asset_id = :asset_id"
        );

        return $stmt->execute([
            'inspection_id' => $inspectionId,
            'asset_id'      => $assetId
        ]);
    }
}