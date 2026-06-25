<?php

namespace App\Services;

use App\Core\Database;
use App\Services\AssetInspection\CreateInspection;
use App\Services\AssetInspection\AddAssets;
use App\Services\AssetInspection\StartInspection;
use App\Services\AssetInspection\CompleteInspection;
use App\Services\AssetInspection\RecordResult;

class AssetInspectionService
{
    public static function create(array $data): array
    {
        $db = Database::getInstance()->getConnection();

        try {
            $db->beginTransaction();

            $inspection = CreateInspection::handle($data);

            if (!empty($data['asset_ids'])) {
                AddAssets::handle($inspection['id'], $data['asset_ids']);
            }

            $db->commit();

            return $inspection;

        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public static function start(int $inspectionId): array
    {
        return StartInspection::handle($inspectionId);
    }

    public static function recordResult(int $inspectionId, int $assetId, array $data): array
    {
        return RecordResult::handle($inspectionId, $assetId, $data);
    }

    public static function complete(int $inspectionId, int $userId): array
    {
        $db = Database::getInstance()->getConnection();

        try {
            $db->beginTransaction();

            $result = CompleteInspection::handle($inspectionId, $userId);

            $db->commit();

            return $result;

        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public static function getAll(): array
    {
        return \App\Models\AssetInspection::getAll();
    }

    public static function getById(int $id): ?array
    {
        return \App\Models\AssetInspection::findById($id);
    }
}