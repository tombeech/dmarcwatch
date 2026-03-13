<?php

namespace App\Models;

use App\Enums\AlertChannelType;
use App\Models\Concerns\BelongsToTeam;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlertChannel extends Model
{
    use BelongsToTeam;

    protected $fillable = [
        'team_id',
        'type',
        'name',
        'config',
        'is_active',
        'is_verified',
    ];

    protected function casts(): array
    {
        return [
            'type' => AlertChannelType::class,
            'config' => 'encrypted:array',
            'is_active' => 'boolean',
            'is_verified' => 'boolean',
        ];
    }

    public function alertRules(): HasMany
    {
        return $this->hasMany(AlertRule::class);
    }

    public function alertLogs(): HasMany
    {
        return $this->hasMany(AlertLog::class);
    }
}
