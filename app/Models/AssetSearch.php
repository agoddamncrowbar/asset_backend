<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class AssetSearch
{
    public static function search(
        array $filters = [],
        int $page = 1,
        int $limit = 20
    ): array {
        $db = Database::getInstance()->getConnection();

        $where = " WHERE 1=1 ";
        $params = [];

        if (!empty($filters['q'])) {
            $where .= "
                AND (
                    a.asset_tag LIKE :asset_tag_search
                    OR a.serial_number LIKE :serial_search
                    OR a.item_name LIKE :item_search
                )
            ";

            $search = '%' . $filters['q'] . '%';

            $params['asset_tag_search'] = $search;
            $params['serial_search'] = $search;
            $params['item_search'] = $search;
        }

        if (!empty($filters['category_id'])) {
            $where .= " AND a.category_id = :category_id";
            $params['category_id'] = (int)$filters['category_id'];
        }

        if (!empty($filters['department_id'])) {
            $where .= " AND a.department_id = :department_id";
            $params['department_id'] = (int)$filters['department_id'];
        }

        if (!empty($filters['location_id'])) {
            $where .= " AND a.location_id = :location_id";
            $params['location_id'] = (int)$filters['location_id'];
        }

        if (!empty($filters['condition_status'])) {
            $where .= " AND a.condition_status = :condition_status";
            $params['condition_status'] = $filters['condition_status'];
        }

        if (!empty($filters['asset_status'])) {
            $where .= " AND a.asset_status = :asset_status";
            $params['asset_status'] = $filters['asset_status'];
        }

        $countSql = "
            SELECT COUNT(*) AS total
            FROM assets a
            {$where}
        ";

        $countStmt = $db->prepare($countSql);
        $countStmt->execute($params);

        $total = (int)$countStmt->fetch(PDO::FETCH_ASSOC)['total'];

        $offset = ($page - 1) * $limit;

        $sql = "
            SELECT
                a.*,
                c.name AS category_name,
                d.name AS department_name,
                l.name AS location_name
            FROM assets a
            LEFT JOIN asset_categories c
                ON a.category_id = c.id
            LEFT JOIN departments d
                ON a.department_id = d.id
            LEFT JOIN asset_locations l
                ON a.location_id = l.id
            {$where}
            ORDER BY a.id DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return [
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'pages' => (int)ceil($total / $limit),
            'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
        ];
    }
}