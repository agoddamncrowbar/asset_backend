<?php

namespace App\Controllers;

use App\Models\SearchUser;

class SearchUserController
{
    private function json(array $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json');

        echo json_encode($data);
        exit;
    }

    /**
     * GET /users/search?q=ABC123
     */
    public function search(): void
    {
        $query = trim($_GET['q'] ?? '');

        if ($query === '') {
            $this->json([
                'success' => false,
                'message' => 'Search query is required'
            ], 400);
        }

        $users = User::search($query);

        $this->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * GET /users/{id}/asset-status
     */
    public function assetStatus(int $id): void
    {
        $user = User::findById($id);

        if (!$user) {
            $this->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $this->json([
            'success' => true,
            'data' => User::getUserAssetStatus($id)
        ]);
    }
}