<?php

namespace App\Services;

use App\Services\Assignments\AssignAsset;
use App\Services\Assignments\ReturnAsset;
use App\Services\Assignments\AssignFromRequest;
use App\Services\Assignments\GetActiveAssignment;
use App\Services\Assignments\GetAssetHistory;
use App\Services\Assignments\GetUserAssignments;

class AssignmentService
{
    public static function assignAsset(
        int $assetId,
        int $assignedTo,
        int $assignedBy,
        ?string $expectedReturnDate = null,
        ?string $notes = null
    ): array {
        return AssignAsset::execute(
            $assetId,
            $assignedTo,
            $assignedBy,
            $expectedReturnDate,
            $notes
        );
    }

    public static function returnAsset(
        int $assignmentId,
        ?string $returnNotes = null,
        ?string $condition = null,
        ?int $processedBy = null
    ): array {
        return ReturnAsset::execute(
            $assignmentId,
            $returnNotes,
            $condition,
            $processedBy
        );
    }

    public static function getActiveAssignment(
        int $assetId
    ): ?array {
        return GetActiveAssignment::execute(
            $assetId
        );
    }

    public static function getAssetHistory(
        int $assetId
    ): array {
        return GetAssetHistory::execute(
            $assetId
        );
    }

    public static function getUserAssignments(
        int $userId
    ): array {
        return GetUserAssignments::execute(
            $userId
        );
    }

    public static function assignFromRequest(
        int $requestId,
        int $assignedBy
    ): array {
        return AssignFromRequest::execute(
            $requestId,
            $assignedBy
        );
    }
}