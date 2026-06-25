<?php

namespace App\Services;

use App\Services\Requests\CreateRequest;
use App\Services\Requests\CancelRequest;
use App\Services\Requests\ApproveRequest;
use App\Services\Requests\RejectRequest;
use App\Services\Requests\GetQueue;
use App\Services\Requests\GetNextQueuedRequest;
use App\Services\Requests\AutoPromoteNext;

class RequestService
{
    public static function createRequest(
        int $assetId,
        int $userId,
        ?string $reason = null
    ): array {
        return CreateRequest::execute($assetId, $userId, $reason);
    }

    public static function cancelRequest(int $requestId): bool
    {
        return CancelRequest::execute($requestId);
    }

    public static function approveRequest(
        int $requestId,
        int $processedBy
    ): array {
        return ApproveRequest::execute($requestId, $processedBy);
    }

    public static function rejectRequest(
        int $requestId,
        int $processedBy
    ): bool {
        return RejectRequest::execute($requestId, $processedBy);
    }

    public static function getQueue(int $assetId): array
    {
        return GetQueue::execute($assetId);
    }

    public static function getNextQueuedRequest(int $assetId): ?array
    {
        return GetNextQueuedRequest::execute($assetId);
    }

    public static function autoPromoteNext(
        int $assetId,
        int $assignedBy
    ): ?array {
        return AutoPromoteNext::execute($assetId, $assignedBy);
    }
}