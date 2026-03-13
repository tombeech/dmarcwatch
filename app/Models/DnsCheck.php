<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DnsCheck extends Model
{
    protected $fillable = [
        'domain_id',
        'record_type',
        'record_value',
        'is_valid',
        'issues',
        'checked_at',
    ];

    protected function casts(): array
    {
        return [
            'is_valid' => 'boolean',
            'issues' => 'array',
            'checked_at' => 'datetime',
        ];
    }

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }
}
