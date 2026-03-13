<?php

namespace App\Models\Concerns;

use App\Models\Team;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTeam
{
    public static function bootBelongsToTeam(): void
    {
        static::addGlobalScope('team', function (Builder $query) {
            $team = auth()->user()?->currentTeam;
            if ($team !== null) {
                $query->where(
                    (new static)->qualifyColumn('team_id'),
                    $team->id
                );
            }
        });
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function scopeForTeam(Builder $query, Team $team): Builder
    {
        return $query->withoutGlobalScope('team')->where('team_id', $team->id);
    }
}
