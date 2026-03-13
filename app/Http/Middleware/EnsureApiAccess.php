<?php

namespace App\Http\Middleware;

use App\Services\PlanLimiter;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiAccess
{
    public function __construct(
        protected PlanLimiter $limiter,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $team = $request->user()?->currentTeam;

        if (! $team || ! $this->limiter->canAccessApi($team)) {
            abort(403, 'API access is not available on your plan.');
        }

        return $next($request);
    }
}
