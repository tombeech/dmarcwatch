<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTeam;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DmarcReport extends Model
{
    use BelongsToTeam;

    protected $fillable = [
        'team_id',
        'domain_id',
        'report_id',
        'reporter_org',
        'reporter_email',
        'date_begin',
        'date_end',
        'domain_policy',
        'subdomain_policy',
        'pct',
        'total_messages',
        'pass_count',
        'fail_count',
        'raw_xml',
        'received_at',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'date_begin' => 'datetime',
            'date_end' => 'datetime',
            'received_at' => 'datetime',
            'processed_at' => 'datetime',
        ];
    }

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    public function records(): HasMany
    {
        return $this->hasMany(ReportRecord::class);
    }
}
