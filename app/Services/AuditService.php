<?php

namespace App\Services;

use App\Core\AuditContext;
use App\Models\AuditLog;
use Throwable;

class AuditService
{
    public static function logCreate(
        string $entityType,
        string|int $entityId,
        array $newValues
    ): void {
        self::write([
            'action' => 'CREATE',
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'old_values' => null,
            'new_values' => $newValues,
            'metadata' => null
        ]);
    }

    public static function logUpdate(
        string $entityType,
        string|int $entityId,
        array $oldValues,
        array $newValues
    ): void {
        self::write([
            'action' => 'UPDATE',
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'metadata' => null
        ]);
    }

    public static function logDelete(
        string $entityType,
        string|int $entityId,
        array $oldValues
    ): void {
        self::write([
            'action' => 'DELETE',
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'old_values' => $oldValues,
            'new_values' => null,
            'metadata' => null
        ]);
    }

    public static function logAction(
        string $action,
        string $entityType,
        string|int|null $entityId = null,
        ?array $metadata = null
    ): void {
        self::write([
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'old_values' => null,
            'new_values' => null,
            'metadata' => $metadata
        ]);
    }

    private static function write(array $data): void
{
    $context = AuditContext::getContext();

    $ok = AuditLog::create([
        'user_id' => $context['user_id'],
        'action' => $data['action'],
        'entity_type' => $data['entity_type'],
        'entity_id' => $data['entity_id'],
        'old_values' => $data['old_values'],
        'new_values' => $data['new_values'],
        'metadata' => $data['metadata'],
        'ip_address' => $context['ip_address'],
        'user_agent' => $context['user_agent']
    ]);

    if (!$ok) {
        error_log('Audit log insert failed');
    }
}
}