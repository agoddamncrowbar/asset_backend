<?php

namespace App\Services;

class AuditFormatter
{
    private const IGNORED_FIELDS = [
        'password_hash',
        'updated_at'
    ];

    private const FIELD_LABELS = [
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'role' => 'Role',
        'status' => 'Status',
        'university_id' => 'University ID',
        'must_change_password' => 'Must Change Password'
    ];

    public static function format(array $log): array
    {
        return [
            'id' => $log['id'],
            'timestamp' => $log['created_at'],
            'user_id' => $log['user_id'],
            'action' => $log['action'],
            'entity_type' => $log['entity_type'],
            'entity_id' => $log['entity_id'],
            'message' => self::buildMessage($log),
            'changes' => self::buildChanges($log)
        ];
    }

    private static function buildMessage(
        array $log
    ): string {
        return match ($log['action']) {
            'CREATE' =>
                sprintf(
                    'Created %s #%s',
                    $log['entity_type'],
                    $log['entity_id']
                ),

            'UPDATE' =>
                sprintf(
                    'Updated %s #%s',
                    $log['entity_type'],
                    $log['entity_id']
                ),

            'DELETE' =>
                sprintf(
                    'Deleted %s #%s',
                    $log['entity_type'],
                    $log['entity_id']
                ),

            default =>
                sprintf(
                    '%s %s #%s',
                    $log['action'],
                    $log['entity_type'],
                    $log['entity_id']
                )
        };
    }

    private static function buildChanges(
        array $log
    ): array {
        return match ($log['action']) {
            'CREATE' => self::buildCreateChanges($log),
            'UPDATE' => self::buildUpdateChanges($log),
            'DELETE' => []
        };
    }

    private static function buildCreateChanges(
        array $log
    ): array {
        $changes = [];

        foreach ($log['new_values'] ?? [] as $field => $value) {

            if (self::shouldIgnoreField($field)) {
                continue;
            }

            $changes[] = [
                'field' => self::label($field),
                'old' => null,
                'new' => $value
            ];
        }

        return $changes;
    }

    private static function buildUpdateChanges(
        array $log
    ): array {
        $changes = [];

        $oldValues = $log['old_values'] ?? [];
        $newValues = $log['new_values'] ?? [];

        foreach ($newValues as $field => $newValue) {

            if (self::shouldIgnoreField($field)) {
                continue;
            }

            $oldValue = $oldValues[$field] ?? null;

            if ($oldValue === $newValue) {
                continue;
            }

            $changes[] = [
                'field' => self::label($field),
                'old' => $oldValue,
                'new' => $newValue
            ];
        }

        return $changes;
    }

    private static function shouldIgnoreField(
        string $field
    ): bool {
        return in_array(
            $field,
            self::IGNORED_FIELDS,
            true
        );
    }

    private static function label(
        string $field
    ): string {
        return self::FIELD_LABELS[$field]
            ?? ucwords(
                str_replace(
                    '_',
                    ' ',
                    $field
                )
            );
    }

    public function getAuditLogs(
        int $limit,
        int $offset
    ): array {

        $logs = AuditLog::getAll($limit, $offset);
        $total = AuditLog::count();

        return [
            'data' => array_map(
                [AuditFormatter::class, 'format'],
                $logs
            ),
            'pagination' => [
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset
            ]
        ];
    }
}