<?php

namespace App\Models;

use Database\Factories\TeamFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Laravel\Cashier\Billable;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;

class Team extends JetstreamTeam
{
    use Billable;
    use HasFactory;

    protected $fillable = [
        'name',
        'personal_team',
        'slug',
        'onboarded_at',
    ];

    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (Team $team) {
            if (empty($team->slug)) {
                $team->slug = static::generateUniqueSlug($team->name);
            }
        });
        static::updating(function (Team $team) {
            if ($team->isDirty('name') && empty($team->slug)) {
                $team->slug = static::generateUniqueSlug($team->name);
            }
        });
    }

    protected static function generateUniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }
        return $slug;
    }

    protected function casts(): array
    {
        return [
            'personal_team' => 'boolean',
            'onboarded_at' => 'datetime',
        ];
    }

    public function domains(): HasMany
    {
        return $this->hasMany(Domain::class);
    }

    public function dmarcReports(): HasMany
    {
        return $this->hasMany(DmarcReport::class);
    }

    public function alertChannels(): HasMany
    {
        return $this->hasMany(AlertChannel::class);
    }

    public function alertRules(): HasMany
    {
        return $this->hasMany(AlertRule::class);
    }
}
