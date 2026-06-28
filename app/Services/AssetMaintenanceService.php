<?php

namespace App\Services;

use App\Core\Database;
use App\Services\AssetMaintenance\CreateJob;
use App\Services\AssetMaintenance\StartJob;
use App\Services\AssetMaintenance\UpdateJob;
use App\Services\AssetMaintenance\CompleteJob;
use App\Models\AssetMaintenanceJob;


class AssetMaintenanceService
{
    public static function create(array $data): array
    {
        return CreateJob::handle($data);
    }

    public static function start(int $jobId): array
    {
        return StartJob::handle($jobId);
    }

    public static function update(int $jobId, array $data): array
    {
        return UpdateJob::handle($jobId, $data);
    }

    public static function complete(int $jobId, array $data): array
    {
        $db = Database::getInstance()->getConnection();

        try {
            $db->beginTransaction();

            $result = CompleteJob::handle($jobId, $data);

            $db->commit();

            return $result;

        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }
    public static function getAll(): array
    {
        return \App\Models\AssetMaintenanceJob::getAll();
    }

    public static function getById(int $id): ?array
    {
        return \App\Models\AssetMaintenanceJob::findById($id);
    }
    public static function getByAssignedUser(int $userId): array
    {
        return AssetMaintenanceJob::getByAssignedUser($userId);
    }
    public static function search(string $query): array
    {
        return AssetMaintenanceJob::search($query);
    }
}