<?php

use App\Jobs\PruneOldReports;
use App\Jobs\ScheduleDnsChecks;
use App\Jobs\SendWeeklyDigests;
use Illuminate\Support\Facades\Schedule;

Schedule::call(fn () => app(ScheduleDnsChecks::class)->handle())
    ->everyMinute()
    ->name('schedule-dns-checks')
    ->withoutOverlapping();

Schedule::job(new PruneOldReports)->daily();
Schedule::job(new SendWeeklyDigests)->weekly()->mondays()->at('09:00');
Schedule::command('horizon:snapshot')->everyFiveMinutes();
