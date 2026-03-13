<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTeam;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ForensicReport extends Model
{
    use BelongsToTeam;

    protected $fillable = [
        'team_id',
        'domain_id',
        'source_ip',
        'arrival_date',
        'auth_failure',
        'delivery_result',
        'dkim_domain',
        'dkim_selector',
        'feedback_type',
        'original_mail_from',
        'original_rcpt_to',
        'subject',
        'sending_source_id',
        'raw_data',
    ];

    protected function casts(): array
    {
        return [
            'arrival_date' => 'datetime',
        ];
    }

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    public function sendingSource(): BelongsTo
    {
        return $this->belongsTo(SendingSource::class);
    }
}
