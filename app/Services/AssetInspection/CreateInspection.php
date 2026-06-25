<?php

namespace App\Services\AssetInspection;

use App\Models\AssetInspection;

class CreateInspection
{
    public static function handle(array $data): array
    {
        return \App\Models\AssetInspection::create([
            'inspection_code' => self::generateCode(),
            'scheduled_date'  => $data['scheduled_date'],
            'status'          => 'scheduled',
            'notes'           => $data['notes'] ?? null,
            'created_by'      => $data['created_by']
        ]);
    }

    private static function generateCode(): string
    {
        return 'INSP-' . date('Ymd') . '-' . str_pad((string)random_int(0, 9999), 4, '0', STR_PAD_LEFT);
    }
}