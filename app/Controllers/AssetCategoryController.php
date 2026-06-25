<?php

namespace App\Controllers;

use App\Models\AssetCategory;
use App\Services\AuditService;

class AssetCategoryController
{
    private function json(array $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json');

        echo json_encode($data);
        exit;
    }

    public function index(): void
    {
        $this->json([
            'success' => true,
            'data' => AssetCategory::getAll()
        ]);
    }

    public function show(int $id): void
    {
        $category = AssetCategory::findById($id);

        if (!$category) {
            $this->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $this->json([
            'success' => true,
            'data' => $category
        ]);
    }

    public function store(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            $this->json([
                'success' => false,
                'message' => 'Invalid JSON body'
            ], 400);
        }

        $required = ['name'];

        foreach ($required as $field) {
            if (empty($input[$field])) {
                $this->json([
                    'success' => false,
                    'message' => "Missing field: {$field}"
                ], 422);
            }
        }

        $created = AssetCategory::create([
            'name' => $input['name'],
            'description' => $input['description'] ?? null,
            'depreciation_period' => $input['depreciation_period'] ?? null
        ]);

        if (!$created) {
            $this->json([
                'success' => false,
                'message' => 'Failed to create category'
            ], 500);
        }

        $id = $created['id'];

        AuditService::logCreate(
            'AssetCategory',
            $id,
            $created
        );

        $this->json([
            'success' => true,
            'message' => 'Category created',
            'data' => $created
        ], 201);
    }

    public function update(int $id): void
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            $this->json([
                'success' => false,
                'message' => 'Invalid JSON body'
            ], 400);
        }

        $old = AssetCategory::findById($id);

        if (!$old) {
            $this->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $updated = AssetCategory::update($id, $input);

        if (!$updated) {
            $this->json([
                'success' => false,
                'message' => 'Failed to update category'
            ], 500);
        }

        AuditService::logUpdate(
            'AssetCategory',
            $id,
            $old,
            $updated
        );

        $this->json([
            'success' => true,
            'message' => 'Category updated',
            'data' => $updated
        ]);
    }

    public function destroy(int $id): void
    {
        $old = AssetCategory::findById($id);

        if (!$old) {
            $this->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $ok = AssetCategory::delete($id);

        if (!$ok) {
            $this->json([
                'success' => false,
                'message' => 'Failed to delete category'
            ], 500);
        }

        AuditService::logDelete(
            'AssetCategory',
            $id,
            $old
        );

        $this->json([
            'success' => true,
            'message' => 'Category deleted'
        ]);
    }
}