<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class InspectionAssets
{
    public static function getAssetIdsByInspection(int $inspectionId): array
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "SELECT asset_id
             FROM asset_inspection_assets
             WHERE inspection_id = :inspection_id
             ORDER BY asset_id ASC"
        );

        $stmt->execute([
            'inspection_id' => $inspectionId
        ]);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}