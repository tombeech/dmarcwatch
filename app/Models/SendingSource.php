<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SendingSource extends Model
{
    protected $fillable = [
        'name',
        'organization',
        'ip_ranges',
        'description',
        'icon',
        'is_system',
    ];

    protected function casts(): array
    {
        return [
            'ip_ranges' => 'array',
            'is_system' => 'boolean',
        ];
    }

    public function reportRecords(): HasMany
    {
        return $this->hasMany(ReportRecord::class);
    }
}
