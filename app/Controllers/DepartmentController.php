<?php

namespace App\Controllers;

use App\Models\Department;
use App\Services\AuditService;

class DepartmentController
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
            'data' => Department::getAll()
        ]);
    }

    public function show(int $id): void
    {
        $department = Department::findById($id);

        if (!$department) {
            $this->json([
                'success' => false,
                'message' => 'Department not found'
            ], 404);
        }

        $this->json([
            'success' => true,
            'data' => $department
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

        $required = ['name', 'code'];

        foreach ($required as $field) {
            if (empty($input[$field])) {
                $this->json([
                    'success' => false,
                    'message' => "Missing field: {$field}"
                ], 422);
            }
        }

        $created = Department::create([
            'name' => $input['name'],
            'code' => $input['code'],
            'description' => $input['description'] ?? null
        ]);

        if (!$created) {
            $this->json([
                'success' => false,
                'message' => 'Failed to create department'
            ], 500);
        }

        $id = $created['id'];

        AuditService::logCreate(
            'Department',
            $id,
            $created
        );

        $this->json([
            'success' => true,
            'message' => 'Department created',
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

        $old = Department::findById($id);

        if (!$old) {
            $this->json([
                'success' => false,
                'message' => 'Department not found'
            ], 404);
        }

        $updated = Department::update($id, $input);

        if (!$updated) {
            $this->json([
                'success' => false,
                'message' => 'Failed to update department'
            ], 500);
        }

        AuditService::logUpdate(
            'Department',
            $id,
            $old,
            $updated
        );

        $this->json([
            'success' => true,
            'message' => 'Department updated',
            'data' => $updated
        ]);
    }

    public function destroy(int $id): void
    {
        $old = Department::findById($id);

        if (!$old) {
            $this->json([
                'success' => false,
                'message' => 'Department not found'
            ], 404);
        }

        $ok = Department::delete($id);

        if (!$ok) {
            $this->json([
                'success' => false,
                'message' => 'Failed to delete department'
            ], 500);
        }

        AuditService::logDelete(
            'Department',
            $id,
            $old
        );

        $this->json([
            'success' => true,
            'message' => 'Department deleted'
        ]);
    }
}