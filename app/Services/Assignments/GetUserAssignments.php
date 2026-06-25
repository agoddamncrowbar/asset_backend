<?php

namespace App\Services\Assignments;

use App\Models\Assignment;

class GetUserAssignments
{
    public static function execute(
        int $userId
    ): array {

        return Assignment::findByUser(
            $userId
        );
    }
}