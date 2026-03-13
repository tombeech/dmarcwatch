<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DomainResource;
use App\Jobs\CheckDomainDns;
use App\Models\Domain;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class DomainController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $domains = Domain::query()
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->orderBy('name')
            ->paginate(25);

        return DomainResource::collection($domains);
    }

    public function store(Request $request): DomainResource
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:253', 'regex:/^[a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?(\.[a-zA-Z]{2,})+$/'],
        ]);

        $team = $request->user()->currentTeam;
        $inboundDomain = config('dmarcwatch.inbound_email.domain', 'reports.dmarcwatch.app');

        $domain = Domain::create([
            'team_id' => $team->id,
            'name' => strtolower($validated['name']),
            'is_active' => true,
            'rua_address' => Str::random(8) . '@' . $inboundDomain,
        ]);

        CheckDomainDns::dispatch($domain);

        return new DomainResource($domain);
    }

    public function show(Domain $domain): DomainResource
    {
        return new DomainResource($domain);
    }

    public function destroy(Domain $domain): JsonResponse
    {
        $domain->delete();
        return response()->json(null, 204);
    }

    public function update(Request $request, Domain $domain): DomainResource
    {
        $validated = $request->validate([
            'is_active' => ['sometimes', 'boolean'],
            'dns_check_interval_minutes' => ['sometimes', 'integer', 'min:60'],
            'dkim_selectors' => ['sometimes', 'array'],
        ]);

        $domain->update($validated);

        return new DomainResource($domain->fresh());
    }

    public function checkDns(Domain $domain): JsonResponse
    {
        CheckDomainDns::dispatch($domain);
        return response()->json(['message' => 'DNS check queued']);
    }
}
