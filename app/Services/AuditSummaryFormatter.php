<?php

namespace App\Services;

class AuditSummaryFormatter
{
    public static function format(
        array $log
    ): array {
        return [
            'id' => $log['id'],
            'timestamp' => $log['created_at'],
            'user_id' => $log['user_id'],
            'action' => $log['action'],
            'entity_type' => $log['entity_type'],
            'entity_id' => $log['entity_id'],
            'summary' => self::buildSummary($log)
        ];
    }

    private static function buildSummary(
        array $log
    ): string {
        $entity = sprintf(
            '%s #%s',
            $log['entity_type'],
            $log['entity_id']
        );

        return match ($log['action']) {

            'CREATE' => sprintf(
                'Created %s',
                $entity
            ),

            'DELETE' => sprintf(
                'Deleted %s',
                $entity
            ),

            'UPDATE' => sprintf(
                'Updated %s (%d field%s changed)',
                $entity,
                self::countChangedFields($log),
                self::countChangedFields($log) === 1
                    ? ''
                    : 's'
            ),

            default => sprintf(
                '%s %s',
                $log['action'],
                $entity
            )
        };
    }

    private static function countChangedFields(
        array $log
    ): int {
        $oldValues = $log['old_values'] ?? [];
        $newValues = $log['new_values'] ?? [];

        $count = 0;

        foreach ($newValues as $field => $newValue) {

            if (in_array(
                $field,
                ['password_hash', 'updated_at'],
                true
            )) {
                continue;
            }

            $oldValue = $oldValues[$field] ?? null;

            if ($oldValue !== $newValue) {
                $count++;
            }
        }

        return $count;
    }
}