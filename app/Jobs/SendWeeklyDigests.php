<?php

namespace App\Jobs;

use App\Models\Team;
use App\Services\PlanLimiter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendWeeklyDigests implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(PlanLimiter $planLimiter): void
    {
        $teams = Team::has('domains')->get();

        foreach ($teams as $team) {
            $limits = $planLimiter->limits($team);

            if (! $limits->weeklyDigests) {
                continue;
            }

            Log::info('[SendWeeklyDigests] Sending digest for team', ['team_id' => $team->id]);

            // TODO: Build and send weekly digest email
        }
    }
}
