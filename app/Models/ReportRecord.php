<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportRecord extends Model
{
    protected $fillable = [
        'dmarc_report_id',
        'source_ip',
        'count',
        'disposition',
        'dkim_result',
        'spf_result',
        'dkim_domain',
        'spf_domain',
        'dkim_aligned',
        'spf_aligned',
        'header_from',
        'envelope_from',
        'sending_source_id',
    ];

    protected function casts(): array
    {
        return [
            'dkim_aligned' => 'boolean',
            'spf_aligned' => 'boolean',
            'disposition' => \App\Enums\Disposition::class,
        ];
    }

    public function dmarcReport(): BelongsTo
    {
        return $this->belongsTo(DmarcReport::class);
    }

    public function sendingSource(): BelongsTo
    {
        return $this->belongsTo(SendingSource::class);
    }
}
