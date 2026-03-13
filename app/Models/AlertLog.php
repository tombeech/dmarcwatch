<?php

namespace App\Models;

use App\Enums\AlertStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlertLog extends Model
{
    protected $fillable = [
        'alert_rule_id',
        'alert_channel_id',
        'event_type',
        'event_data',
        'status',
        'response',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'event_data' => 'array',
            'status' => AlertStatus::class,
            'sent_at' => 'datetime',
        ];
    }

    public function alertRule(): BelongsTo
    {
        return $this->belongsTo(AlertRule::class);
    }

    public function alertChannel(): BelongsTo
    {
        return $this->belongsTo(AlertChannel::class);
    }
}
