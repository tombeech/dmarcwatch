<?php

namespace App\Jobs;

use App\Models\DmarcReport;
use App\Models\Team;
use App\Services\PlanLimiter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PruneOldReports implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(PlanLimiter $planLimiter): void
    {
        $teams = Team::has('dmarcReports')->get();

        foreach ($teams as $team) {
            $retentionDays = $planLimiter->getRetentionDays($team);

            if ($retentionDays === null) {
                continue; // Unlimited retention
            }

            $cutoff = now()->subDays($retentionDays);

            $deleted = DmarcReport::withoutGlobalScope('team')
                ->where('team_id', $team->id)
                ->where('created_at', '<', $cutoff)
                ->delete();

            if ($deleted > 0) {
                Log::info('[PruneOldReports] Pruned old reports', [
                    'team_id' => $team->id,
                    'deleted' => $deleted,
                    'retention_days' => $retentionDays,
                ]);
            }
        }
    }
}
