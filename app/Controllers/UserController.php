<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\AuditService;

class UserController
{
    private function json(array $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json');

        echo json_encode($data);
        exit;
    }

    public function index()
    {
        $users = User::getAll();

        $this->json([
            'success' => true,
            'data' => $users
        ]);
    }

    public function show($id)
    {
        $user = User::findById((int) $id);

        if (!$user) {
            $this->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $this->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function store()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            $this->json([
                'success' => false,
                'message' => 'Invalid JSON body'
            ], 400);
        }

        $required = [
            'university_id',
            'first_name',
            'last_name',
            'email',
            'password',
            'role'
        ];

        foreach ($required as $field) {
            if (empty($input[$field])) {
                $this->json([
                    'success' => false,
                    'message' => "Missing field: {$field}"
                ], 422);
            }
        }

        $allowedRoles = ['student', 'faculty', 'technologist', 'admin'];

        if (!in_array($input['role'], $allowedRoles, true)) {
            $this->json([
                'success' => false,
                'message' => 'Invalid role selected'
            ], 422);
        }

        $currentUser = \App\Helpers\Auth::user();

        if ($input['role'] === 'admin' && ($currentUser['role'] ?? null) !== 'admin') {
            $this->json([
                'success' => false,
                'message' => 'Not allowed to assign admin role'
            ], 403);
        }

        $id = User::create([
            'university_id' => $input['university_id'],
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'email' => $input['email'],
            'phone' => $input['phone'] ?? null,
            'password_hash' => password_hash($input['password'], PASSWORD_BCRYPT),
            'role' => $input['role'],
            'status' => $input['status'] ?? 'active',
            'must_change_password' => 1
        ]);

        if (!$id) {
            $this->json([
                'success' => false,
                'message' => 'Failed to create user'
            ], 500);
        }

        $newUser = User::findById($id);

        AuditService::logCreate(
            'User',
            $id,
            $newUser
        );

        $this->json([
            'success' => true,
            'message' => 'User created',
            'id' => $id
        ], 201);
    }

    public function update($id)
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            $this->json([
                'success' => false,
                'message' => 'Invalid JSON body'
            ], 400);
        }

        $oldUser = User::findById((int) $id);

        if (!$oldUser) {
            $this->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $updated = User::update((int) $id, $input);

        if (!$updated) {
            $this->json([
                'success' => false,
                'message' => 'Failed to update user'
            ], 500);
        }

        $newUser = User::findById((int) $id);

        AuditService::logUpdate(
            'User',
            $id,
            $oldUser,
            $newUser
        );

        $this->json([
            'success' => true,
            'message' => 'User updated'
        ]);
    }

    public function suspend($id)
    {
        $oldUser = User::findById((int) $id);

        if (!$oldUser) {
            $this->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $ok = User::suspend((int) $id);

        if (!$ok) {
            $this->json([
                'success' => false,
                'message' => 'Failed to suspend user'
            ], 500);
        }

        $newUser = User::findById((int) $id);

        AuditService::logUpdate(
            'User',
            $id,
            $oldUser,
            $newUser
        );

        $this->json([
            'success' => true,
            'message' => 'User suspended'
        ]);
    }

    public function activate($id)
    {
        $oldUser = User::findById((int) $id);

        if (!$oldUser) {
            $this->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $ok = User::activate((int) $id);

        if (!$ok) {
            $this->json([
                'success' => false,
                'message' => 'Failed to activate user'
            ], 500);
        }

        $newUser = User::findById((int) $id);

        AuditService::logUpdate(
            'User',
            $id,
            $oldUser,
            $newUser
        );

        $this->json([
            'success' => true,
            'message' => 'User activated'
        ]);
    }

    public function destroy($id)
    {
        $user = User::findById((int) $id);

        if (!$user) {
            $this->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $ok = User::delete((int) $id);

        if (!$ok) {
            $this->json([
                'success' => false,
                'message' => 'Failed to delete user'
            ], 500);
        }

        AuditService::logDelete(
            'User',
            $id,
            $user
        );

        $this->json([
            'success' => true,
            'message' => 'User deleted'
        ]);
    }
}