<?php

namespace App\Services;

use App\Models\Asset;
use App\Core\Database;
use PDO;
use Exception;

class AssetService
{
    /**
     * Create asset with tag generation + FK validation
     */
    public function createAsset(array $data, int $userId): array
    {
        $this->validateRequiredCreateFields($data);
        $this->validateForeignKeys($data);

        $assetTag = $this->generateAssetTag(
            $data['department_id'],
            $data['category_id']
        );

        $assetId = Asset::create([
            'asset_tag' => $assetTag,
            'serial_number' => $data['serial_number'] ?? null,
            'item_name' => $data['item_name'],
            'description' => $data['description'] ?? null,
            'category_id' => $data['category_id'],
            'department_id' => $data['department_id'],
            'location_id' => $data['location_id'],
            'purchase_date' => $data['purchase_date'] ?? null,
            'purchase_cost' => $data['purchase_cost'] ?? null,
            'supplier' => $data['supplier'] ?? null,
            'condition_status' => $data['condition_status'] ?? 'good',
            'asset_status' => 'available',
            'created_by' => $userId
        ]);

        return Asset::findById($assetId);
    }

    /**
     * Get single asset
     */
    public function getAssetById(int $id): ?array
    {
        if ($id <= 0) {
            throw new Exception("Invalid asset ID");
        }

        return Asset::findById($id);
    }

    /**
     * Get paginated assets
     */
    public function getAllAssets(int $limit = 50, int $offset = 0): array
    {
        $limit = max(1, min($limit, 200));
        $offset = max(0, $offset);

        return Asset::findAll($limit, $offset);
    }

    /**
     * Update asset with lifecycle protection
     */
    public function updateAsset(int $id, array $data): array
    {
        $asset = Asset::findById($id);

        if (!$asset) {
            throw new Exception("Asset not found");
        }

        if ($asset['asset_status'] === 'retired') {
            throw new Exception("Cannot modify a retired asset");
        }

        if ($this->hasForeignKeyChanges($data)) {
            $this->validateForeignKeys($data);
        }

        $ok = Asset::update($id, $data);

        if (!$ok) {
            throw new Exception("Failed to update asset");
        }

        $updated = Asset::findById($id);

        return [
            'old' => $asset,
            'new' => $updated
        ];
    }

    /**
     * Retire asset (soft delete)
     */
    public function retireAsset(int $id): array
    {
        $asset = Asset::findById($id);

        if (!$asset) {
            throw new Exception("Asset not found");
        }

        if ($asset['asset_status'] === 'retired') {
            return [
                'old' => $asset,
                'new' => $asset
            ];
        }

        $ok = Asset::retire($id);

        if (!$ok) {
            throw new Exception("Failed to retire asset");
        }

        $updated = Asset::findById($id);

        return [
            'old' => $asset,
            'new' => $updated
        ];
    }

    /**
     * Generate asset tag
     */
    private function generateAssetTag(int $departmentId, int $categoryId): string
    {
        $year = date('Y');

        $deptCode = $this->getDepartmentCode($departmentId);
        $catCode = $this->getCategoryCode($categoryId);

        $count = $this->getYearlyAssetCount($year);

        $sequence = str_pad((string)($count + 1), 6, '0', STR_PAD_LEFT);

        return "USIU-{$deptCode}-{$catCode}-{$year}-{$sequence}";
    }

    /**
     * Count assets for sequence generation
     */
    private function getYearlyAssetCount(int $year): int
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            SELECT COUNT(*)
            FROM assets
            WHERE YEAR(created_at) = :year
        ");

        $stmt->execute(['year' => $year]);

        return (int) $stmt->fetchColumn();
    }

    /**
     * Department ID lookup
     */
    private function getDepartmentCode(int $id): string
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            SELECT id
            FROM departments
            WHERE id = :id
        ");

        $stmt->execute(['id' => $id]);

        $code = $stmt->fetchColumn();

        if (!$code) {
            throw new Exception("Invalid department ID");
        }

        return strtoupper($code);
    }

    /**
     * Category ID lookup
     */
    private function getCategoryCode(int $id): string
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            SELECT id
            FROM asset_categories
            WHERE id = :id
        ");

        $stmt->execute(['id' => $id]);

        $code = $stmt->fetchColumn();

        if (!$code) {
            throw new Exception("Invalid category ID");
        }

        return strtoupper($code);
    }

    /**
     * FK validation (only what is needed)
     */
    private function validateForeignKeys(array $data): void
    {
        $this->assertExists('departments', $data['department_id']);
        $this->assertExists('asset_categories', $data['category_id']);
        $this->assertExists('asset_locations', $data['location_id']);
    }

    private function assertExists(string $table, int $id): void
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            SELECT id
            FROM {$table}
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute(['id' => $id]);

        if (!$stmt->fetchColumn()) {
            throw new Exception("Invalid reference in {$table}: {$id}");
        }
    }

    /**
     * Detect if FK validation is needed on update
     */
    private function hasForeignKeyChanges(array $data): bool
    {
        return isset($data['department_id'])
            || isset($data['category_id'])
            || isset($data['location_id']);
    }

    /**
     * Basic required field validation
     */
    private function validateRequiredCreateFields(array $data): void
    {
        $required = [
            'item_name',
            'category_id',
            'department_id',
            'location_id'
        ];

        foreach ($required as $field) {
            if (!isset($data[$field])) {
                throw new Exception("Missing required field: {$field}");
            }
        }
    }
}