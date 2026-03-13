<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTeam;
use Database\Factories\DomainFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Domain extends Model
{
    use BelongsToTeam;

    /** @use HasFactory<DomainFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'team_id',
        'name',
        'is_active',
        'rua_address',
        'dmarc_record',
        'spf_record',
        'dkim_selectors',
        'dmarc_policy',
        'spf_status',
        'dkim_status',
        'compliance_score',
        'last_dns_check_at',
        'next_dns_check_at',
        'dns_check_interval_minutes',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'dkim_selectors' => 'array',
            'compliance_score' => 'decimal:2',
            'last_dns_check_at' => 'datetime',
            'next_dns_check_at' => 'datetime',
        ];
    }

    public function dmarcReports(): HasMany
    {
        return $this->hasMany(DmarcReport::class);
    }

    public function forensicReports(): HasMany
    {
        return $this->hasMany(ForensicReport::class);
    }

    public function dnsChecks(): HasMany
    {
        return $this->hasMany(DnsCheck::class);
    }
}
