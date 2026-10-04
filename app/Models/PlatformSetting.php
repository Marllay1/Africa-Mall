<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['maintenance_mode', 'maintenance_message', 'min_withdrawal_amount', 'support_email'])]
class PlatformSetting extends Model
{
    protected function casts(): array
    {
        return [
            'maintenance_mode' => 'boolean',
        ];
    }

    /**
     * The single settings row, created by the migration — always exists.
     */
    public static function current(): self
    {
        return static::query()->firstOrFail();
    }
}
