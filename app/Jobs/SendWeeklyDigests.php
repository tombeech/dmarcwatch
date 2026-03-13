<?php

namespace App\Jobs;

use App\Mail\WeeklyDigest;
use App\Models\DmarcReport;
use App\Models\ReportRecord;
use App\Models\SendingSource;
use App\Models\Team;
use App\Services\ComplianceScorer;
use App\Services\PlanLimiter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

            $since = now()->subDays(7);

            $reports = DmarcReport::where('team_id', $team->id)
                ->where('created_at', '>=', $since)
                ->with(['records', 'domain'])
                ->get();

            $totalReports = $reports->count();
            $totalMessages = $reports->sum('total_messages');
            $totalPass = $reports->sum('pass_count');
            $passRate = $totalMessages > 0 ? round(($totalPass / $totalMessages) * 100, 2) : 0;

            $domainStats = $reports->groupBy('domain_id')->map(function (\Illuminate\Support\Collection $domainReports) {
                $domain = $domainReports->first()->domain;
                $messages = $domainReports->sum('total_messages');
                $pass = $domainReports->sum('pass_count');

                return [
                    'domain' => $domain->name,
                    'reports' => $domainReports->count(),
                    'messages' => $messages,
                    'pass_rate' => $messages > 0 ? round(($pass / $messages) * 100, 2) : 0,
                    'compliance_score' => $domain->compliance_score,
                ];
            })->values()->toArray();

            $existingSourceIds = SendingSource::whereHas('reportRecords.dmarcReport', function ($q) use ($team, $since) {
                $q->where('team_id', $team->id)
                    ->where('created_at', '<', $since);
            })->pluck('id');

            $newSources = SendingSource::whereHas('reportRecords.dmarcReport', function ($q) use ($team, $since) {
                $q->where('team_id', $team->id)
                    ->where('created_at', '>=', $since);
            })->whereNotIn('id', $existingSourceIds)->get()
                ->map(fn ($source) => [
                    'name' => $source->name,
                    'organization' => $source->organization,
                ])->toArray();

            $stats = [
                'total_reports' => $totalReports,
                'total_messages' => $totalMessages,
                'pass_rate' => $passRate,
                'domain_stats' => $domainStats,
                'new_sources' => $newSources,
            ];

            $owner = $team->owner;

            if ($owner) {
                Mail::to($owner)->send(new WeeklyDigest($team, $stats));
            }
        }
    }
}
