<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTeam;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlertRule extends Model
{
    use BelongsToTeam;

    protected $fillable = [
        'team_id',
        'alert_channel_id',
        'domain_id',
        'event_types',
        'threshold_type',
        'threshold_value',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'event_types' => 'array',
            'is_active' => 'boolean',
            'threshold_value' => 'decimal:2',
        ];
    }

    public function alertChannel(): BelongsTo
    {
        return $this->belongsTo(AlertChannel::class);
    }

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    public function alertLogs(): HasMany
    {
        return $this->hasMany(AlertLog::class);
    }
}
