<?php

namespace App\Http\Middleware;

use App\Services\PlanLimiter;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforcePlanLimits
{
    public function __construct(
        protected PlanLimiter $limiter,
    ) {}

    public function handle(Request $request, Closure $next, string $resource = ''): Response
    {
        if ($request->method() !== 'POST') {
            return $next($request);
        }

        $team = $request->user()?->currentTeam;

        if (! $team) {
            return $next($request);
        }

        $allowed = match ($resource) {
            'domain' => $this->limiter->canAddDomain($team),
            'alert-channel' => $this->limiter->canAddAlertChannel($team),
            default => true,
        };

        if (! $allowed) {
            abort(403, 'Plan limit reached. Please upgrade your plan.');
        }

        return $next($request);
    }
}
