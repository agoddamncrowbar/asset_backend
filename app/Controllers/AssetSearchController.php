<?php

namespace App\Controllers;

use App\Models\AssetSearch;

class AssetSearchController
{
    private function json(array $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json');

        echo json_encode($data);
        exit;
    }

    public function search(): void
    {
        $filters = [
            'q' => $_GET['q'] ?? null,
            'category_id' => $_GET['category_id'] ?? null,
            'department_id' => $_GET['department_id'] ?? null,
            'location_id' => $_GET['location_id'] ?? null,
            'condition_status' => $_GET['condition_status'] ?? null,
            'asset_status' => $_GET['asset_status'] ?? null,
        ];

        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = max(1, min(100, (int)($_GET['limit'] ?? 20)));

        $results = AssetSearch::search(
            $filters,
            $page,
            $limit
        );

        $this->json([
            'success' => true,
            'page' => $results['page'],
            'limit' => $results['limit'],
            'total' => $results['total'],
            'pages' => $results['pages'],
            'count' => count($results['data']),
            'data' => $results['data']
        ]);
    }
}